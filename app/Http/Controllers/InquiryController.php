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

        // dd($data);
        $data['patient']['drPhotoUrl'] = public_path('/img/avatar.png');

        $data['patient']['lic'] = $request->user()->lic;
        $data['patient']['ptr'] = $request->user()->ptr;
        $data['patient']['s2'] = $request->user()->s2;
        
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

        if( $data['appointState'] == "Teleconsultation"){
            array_push( 
                $updateArray, 
                ['path' => 'teleconsultFee', 'value' => $data['payments']],
            );
        }
        else{
            array_push(
                $updateArray, 
                ['path' => 'consultFee', 'value' => $data['payments']],
            );
        }

        $signatureName = Str::upper(Str::random(6));
        $signature = $request->user()->signature;
        $ext = $this->downloadSignature($signatureName, $signature);

        $data['signature'] =  '/' . $signatureName . $ext;
        
        try{
            $pdf = PDF::loadView('pdf/prescribe', $data);
        }
        catch(\Exception $e){
            dd($e);
            return response()->json([
                'hasError' => true,
            ]);
        }

        $name = Str::upper(Str::random(6));
        $path = '/' . $name . '.pdf';
        
        Storage::disk('public_pdf')->put($path, $pdf->output()); 
        
        unlink( public_path('pdf') . '/' . $signatureName . $ext);
        // try{
        //     $this->firestore->database()->collection('AppointmentList')->document($data['id'])->update($updateArray);
        // }
        // catch(\Exception $e){
        //     return response()->json([
        //         'hasError' => true,
        //     ]);
        // }


        // $pdf = PDF::loadView('pdf/prescribe', $data);


        // try{
        //     Mail::to($email)->send(new InquiryMail($data, $name . '.pdf'));
        // }
        // catch(\Exception $e){
            
        //     unlink( public_path('pdf') . $path);

        //     return response()->json([
        //         'hasError' => true,
        //     ]);
        // }
        

        return response()->json([
            'hasError' => true, // false
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
        $data['checkedLab'] = json_decode($data['checkedLab']);

        $data['lic'] = $request->user()->lic;
        $data['ptr'] = $request->user()->ptr;
        $data['s2'] = $request->user()->s2;
        
        $email = $this->firestore->database()->collection("Patients")->document($data['patient']->pId)->snapshot()->data()['email'];
        
        // dd($data);

        $signatureName = Str::upper(Str::random(6));
        $signature = $request->user()->signature;
        $ext = $this->downloadSignature($signatureName, $signature);

        $data['signature'] =  '/' . $signatureName . $ext;
        
        try{
            $pdf = PDF::loadView('pdf/lab', $data);
        }
        catch(\Exception $e){
            dd($e);
            return response()->json([
                'hasError' => true,
            ]);
        }

        $name = Str::upper(Str::random(6)) ;
        $path = '/' . $name . '.pdf';

        Storage::disk('public_pdf')->put($path, $pdf->output());
        
        unlink( public_path('pdf') . '/' . $signatureName . $ext);
        
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
            'hasError' => false, // false talaga to
        ]);
        
    }

    public function addFile($file, $id){

        $firebase_storage_path = 'Doctors Folder/' . $id . '/';  
        $localfolder = public_path('firebase-temp-uploads') .'/';  
        $sample = '';

        $fileName = $file->hashName(); 
        
        // $index = strrpos($fileName , ".");

        // $name = substr($fileName, 0 , $index);

        if ($file->move($localfolder, $fileName)) {  

            $uploadedfile = fopen($localfolder.$fileName, 'r');  
            $sample = $this->storage->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $fileName]);
              
            unlink($localfolder . $fileName); 
        }

        return $sample->identity()['object'];
    }
    
    public function deleteObject($objectName){ $this->storage->getBucket()->object($objectName)->delete(); }

    public function changeSignature(Request $request){
        $user = $request->user();

        if( $user->signature != '' && $user->signature != null){
            $this->deleteObject($user->signature);
        }

        $file = $request->signature;
        $user->signature = $this->addFile($file, $user->id_fb);
        $user->save();
        
        $updateArray = [
            ['path' => 'signature', 'value' => $user->signature],
        ];
        $this->firestore->database()->collection('Doctors')->document($user->id_fb)->update($updateArray);
        
        try{
            $signature = '';
            $object = $this->storage->getBucket()->object($user->signature);

            if($object->exists()){
                $signature = $object->signedUrl(now()->addDays(1));
            }
            
            return response()->json([
                'hasError' => false,
                'signature' => $signature,
            ]);
        }
        catch (Throwable $e){
            return ['hasError' => true];
        }
    }

    public function downloadSignature($name, $signature){
        // $signature = 'https://firebasestorage.googleapis.com/v0/b/teledoc-ee4b7.appspot.com/o/Doctors%20Folder%2F5hDT6u1nkkf5zrCyMdvJZkOIg1Z2%2F5hDT6u1nkkf5zrCyMdvJZkOIg1Z2_signature?alt=media&token=fa1ec167-e533-4afb-a656-37f731656a3e';
        $sub = substr($signature, 0, 5);
        $ext = '';

        if($sub != 'https'){
            $signature = $this->storage->getBucket()->object($signature);

            $objectName = $signature->info()['name'];
            $ext = substr($objectName, strrpos($objectName, '.'));

            if($signature->exists()){
                $signature = $signature->downloadAsStream();
            }

            Storage::disk('public_pdf')->put(  '/' . $name . $ext, $signature->getContents());
        }
        else{
            $image = file_get_contents($signature);
            $ext = '.png';

            Storage::disk('public_pdf')->put(  '/' . $name . $ext, $image);
        }

        return $ext;
    }
}
// YunaShin@gmail.com