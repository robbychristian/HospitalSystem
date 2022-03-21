<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Firestore;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use Firebase;
use App\Mail\InquiryMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PDF;
use Illuminate\Support\Str;


class InquiryController extends Controller
{
    
    public function __construct(Firestore $firestore){ 
        $this->firestore = $firestore; 
        $this->storage = Firebase::storage();
        $this->middleware('auth')->except('download');
        $this->middleware('verified')->except('download');
    }

    public function index(){
        $page="Laboratory Request & Prescription";
        $active="inquiry";

        $documents = $this->firestore->database()->collection('AppointmentList')->where('drId', '==', Auth::user()->id_fb)->documents()->rows();

        $patient = [];
        $num = 1;

        foreach ($documents as $document) {
            $data = $document->data();

            if( ( $data['prescribeState'] == 'no' && $data['appointStatus'] == 'Approved' ) || ( $data['labRequest'] == '1' && $data['appointStatus'] == 'Approved' && $data['prescribeState'] == 'no' ) ){
                $data['id'] = $document->id();
                $data['name'] = $data['pfName'] . ' ' . $data['plName'];
                $data['pno'] = $num;
    
                $num = $num + 1;
    
                array_push(
                    $patient,
                    $data
                );
            }
        }

        $patient = json_encode($patient);

        return view('pages.inquiry')->with('page', $page)->with('active', $active)->with('patient', $patient);
    }

    public function signedUrl(Request $request){
        try{   
            $image = '';
            $object = $this->storage->getBucket()->object($request->image);

            if($object->exists()){
                $image = $object->signedUrl(now()->addDays(1));
            }
            
            return response()->json([
                'hasError' => false,
                'image' => $image
            ]);
        }
        catch (Throwable $e){
            return ['hasError' => true];
        }
    }

    public function prescribe(Request $request){
        $data = $request->all();
        
        $medicines = [];

        foreach ($data['medicine'] as $item) {
            array_push( $medicines, $item['prescribed'] );
        }

        $updateArray = [
            ['path' => 'medicines', 'value' => $medicines],
            ['path' => 'actualProblem', 'value' => $data['actualProblem']],
            ['path' => 'rx', 'value' => $data['rx']],
            ['path' => 'advice', 'value' => $data['advice']],
            ['path' => 'prescribeState', 'value' => 'yes'],
            ['path' => 'prescribeDate', 'value' => now()->format('m/d/Y')],
        ];
        
        try{
            $this->firestore->database()->collection('AppointmentList')->document($data['id'])->update($updateArray);
        }
        catch(\Exception $e){
            return response()->json([
                'hasError' => true,
            ]);
        }

        return response()->json([
            'hasError' => false,
        ]);
    }

    public function download($pdf){
        $path = public_path('pdf') . '/' . $pdf;
        $name = 'Lab Form';
        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function send(Request $request){
        $data = $request->all();

        $data['patient'] = json_decode($data['patient']);
        $data['chemProfOptions'] = json_decode($data['chemProfOptions']);
        $data['chemOptions'] = json_decode($data['chemOptions']);
        $data['glucoseOptions'] = json_decode($data['glucoseOptions']);
        
        $data['hemaOptions'] = json_decode($data['hemaOptions']);
        $data['urineOptions'] = json_decode($data['urineOptions']);
        $data['bodyFluidsOptions'] = json_decode($data['bodyFluidsOptions']);
        
        $data['labform'] = json_decode($data['labform']);
        
        $email = $this->firestore->database()->collection("Patients")->document($data['patient']->pId)->snapshot()->data()['email'];

        // dd($data);

        $pdf = PDF::loadView('pdf/lab', $data);

        $name = Str::upper(Str::random(6)) ;
        $path = '/' . $name . '.pdf';

        Storage::disk('public_pdf')->put($path, $pdf->output()); 
        
        try{
            Mail::to($email)->send(new InquiryMail($data, $name . '.pdf'));

            $updateArray = [
                ['path' => 'labRequest', 'value' => '2'],
            ];
            $this->firestore->database()->collection("AppointmentList")->document($data['patient']->id)->update($updateArray);
            
        }
        catch(\Exception $e){
            
            unlink( public_path('pdf') . $path);

            return response()->json([
                'hasError' => true,
            ]);
        }

        return response()->json([
            'hasError' => false,
        ]);
        
    }
}
// YunaShin@gmail.com