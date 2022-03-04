<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Firestore;
use Kreait\Firebase\Contract\Storage;
use Carbon\Carbon;

class InquiryController extends Controller
{
    public function __construct(Firestore $firestore, Storage $storage){ 
        $this->firestore = $firestore; 
        $this->storage = $storage;
        $this->middleware('auth');
        $this->middleware('verified');
    }

    public function index(){
        $page="Laboratory Request & Prescription";
        $active="inquiry";


        // $documents = $this->firestore->database()->collection('Doctors')->document(<doctor id>)->
        // ->collection('Patients')->documents()->rows();
        $documents = $this->firestore->database()->collection('Patients')->documents()->rows();

        $patient = [];

        foreach ($documents as $document) {
            $data = $document->data();
            $data['id'] = $document->id();
            $data['joindate'] = Carbon::parse($data['joinDate'])->format('F d, Y');
            $data['age'] = Carbon::parse($data['birthdate'])->diff(Carbon::now())->y;
            $data['name'] = $data['fname'] . ' ' . $data['lname'];

            array_push(
                $patient,
                $data
            );
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
}
