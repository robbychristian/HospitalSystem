<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Firestore;
use Kreait\Firebase\Contract\Storage;
use Kreait\Firebase\Contract\Auth;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    public function __construct(Firestore $firestore, Storage $storage, Auth $auth){ 
        $this->firestore = $firestore; 
        $this->storage = $storage;
        $this->auth = $auth;
        $this->middleware('auth');
        $this->middleware('verified');
    }

    public function index(){
        $page="Patient Records";
        $active="patient";

        $documents = $this->firestore->database()->collection('Patients')->documents()->rows();

        $patient = [];

        foreach ($documents as $document) {
            $data = $document->data();
            $data['id'] = $document->id();
            $data['age'] = (string)Carbon::parse($data['birthdate'])->diff(Carbon::now())->y;
            $data['name'] = $data['fname'] . ' ' . $data['lname'];

            array_push(
                $patient,
                $data
            );
        }
        $patient = json_encode($patient);
        
        return view('pages.patient')->with('page', $page)->with('active', $active)->with('patient', $patient);
    }
    

    public function create($data, $request){
        $user = $this->firestore->database()->collection('Patients')->newDocument();

        $name = $data['firstName'] . ' ' . $data['lastName'];
        $data['password'] = "Qwerty@123";
        $data['joindate'] = now()->format('d-m-Y');
        $data['photo'] = $this->addFile($request->photo, $user->id());

        $user->set([
            'id' => $user->id(),
            'name' => $name,
            'fname' => $data['firstName'],
            'lname' => $data['lastName'],
            'phone' => $data['phone'],
            'gender' => $data['gender'] == 'true' ? "Male" : "Female",
            'email' => $data['email'],
            'address' => $data['address'],
            'nationality' => (string) $data['nationality'],
            'imageUrl' => $data['photo'],
            'bloodType' => $data['bloodType'],
            'password' => $data['password'],
            'joinDate' => $data['joindate'],
            'birthdate' => $data['birthdate'],
            'role' => "Patient",
            'takenTeleService' => null,
        ]);

        $userProperties = [
            'email' => $data['email'],
            'password' => "Qwerty@123",
            'uid' => $user->id(),
        ];

        $this->auth->createUser($userProperties);
        
        $data = $user->snapshot()->data();
        $data['id'] = $user->id();
        $data['age'] = Carbon::parse($data['birthdate'])->diff(Carbon::now())->y;

        return $data;
    }

    public function validation( array $data, $withEmail){
        if($withEmail){
            return Validator::make($data, [
                'address' => ['required', 'string', 'max:255'],
                'nationality' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'max:255', 'email' ],
                'firstName' => ['required', 'regex:/^[\pL\s\-]+$/u', 'max:255', ],
                'lastName' => ['required', 'regex:/^[\pL\s\-]+$/u', 'max:255', ],
                'photo' => ['required ', 'image', 'mimes:jpg,png,jpeg', ],
                'phone' => ['required', 'numeric', 'digits_between:9,11', ],
                'birthdate' => ['required', 'string', 'max:255' ],
            ]);
        }

        return Validator::make($data, [
            'address' => ['required', 'string', 'max:255'],
            'nationality' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'max:255', 'email' ],
            'firstName' => ['required', 'regex:/^[\pL\s\-]+$/u', 'max:255', ],
            'lastName' => ['required', 'regex:/^[\pL\s\-]+$/u', 'max:255', ],
            'photo' => ['nullable ', 'image', 'mimes:jpg,png,jpeg', ],
            'phone' => ['required', 'numeric', 'digits_between:9,11', ],
            'birthdate' => ['required', 'string', 'max:255' ],
            // 'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function addFile($file, $id){

        $firebase_storage_path = 'Patients Folder/' . $id . '/';  
        $localfolder = public_path('firebase-temp-uploads') .'/';  
        $sample = '';

        $fileName = $file->hashName(); 
    
        if ($file->move($localfolder, $fileName)) {  

            $uploadedfile = fopen($localfolder.$fileName, 'r');  
            $sample = $this->storage->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $fileName]);
              
            unlink($localfolder . $fileName); 
        }

        return $sample->identity()['object'];
    }


    public function add(Request $request){
        $data = $request->all();
        
        // $validator = $this->validation($data, $data['withEmail']);
        $validator = $this->validation($data, true);
        if($validator->fails()){
            return $validator->errors()->add('hasError', true);
        }
        
        $patient = $this->create($data, $request);
        
        if($patient == null){
            return ['error' => 'error in database please make sure you have internet or refresh the page!'];
        }

        return [
            'success' => 'Success! Patient has been added!',
            'patient' => json_encode($patient),
        ];

    }
}
