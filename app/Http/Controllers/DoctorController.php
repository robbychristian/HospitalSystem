<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Kreait\Firebase\Contract\Firestore;
use Kreait\Firebase\Contract\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DoctorController extends Controller
{
    public function __construct(Firestore $firestore, Storage $storage){ 
        $this->firestore = $firestore; 
        $this->storage = $storage;
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function create($data){
        $user = $this->firestore->database()->collection('Doctors')->newDocument();

        $name  = $data['firstName'] . ' ' . $data['lastName'];
        $data['password'] = "Qwerty@123";
        $data['joindate'] = now();

        $user->set([
            'fName' => $data['firstName'],
            'lName' => $data['lastName'],
            'phone' => $data['phone'],
            'gender' => $data['gender'] == 'true' ? true : false,
            'about' => $data['about'],
            'email' => $data['email'],
            'clinicAddress' => $data['clinicAddress'],
            'consultFee' => $data['consultFee'],
            'specialization' => $data['specialization'],
            'photoUrl' => $data['photo'],
            'degree' => $data['degree'],
            'password' => $data['password'],
            'joinDate' => now(),
            'isAdmin' => 0,
            'isVerified' => 0,
            'totalPrescribe' => 0,
            'totalEarnings' => 0,
            'provideTeleService' => true,
            'teleconsultFee' => 0,
        ]);

        $userModel = User::create([
            'name' => $name,
            'id_fb' => $user->id(),
            'fname' => $data['firstName'],
            'lname' => $data['lastName'],
            'phone' => $data['phone'],
            'gender' => $data['gender'] == 'true' ? true : false,
            'about' => $data['about'],
            'email' => $data['email'],
            'clinicAddress' => $data['clinicAddress'],
            'consultFee' => $data['consultFee'],
            'specialization' => $data['specialization'],
            'photoUrl' => $data['photo'],
            'degree' => $data['degree'],
            'password' => Hash::make($data['password']),
            'joinDate' => $data['joindate'],
        ]);

        return $userModel;
    }

    public function validation( array $data){
        return Validator::make($data, [
            'clinicAddress' => ['required', 'string', 'max:255'],
            'consultFee' => ['required', 'integer',],
            'specialization' => ['required', 'string', 'max:255', ],
            'firstName' => ['required', 'regex:/^[\pL\s\-]+$/u', 'max:255', ],
            'lastName' => ['required', 'regex:/^[\pL\s\-]+$/u', 'max:255', ],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'degree' => ['required', 'file', 'mimes:pptx,docx,doc,pdf', ],
            'photo' => ['required', 'image', 'mimes:jpg,png,jpeg', ],
            'phone' => ['required', 'numeric', 'digits_between:9,11', ],
            // 'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function addFile($file){

        $firebase_storage_path = 'Doctors/';  
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

    public function add(Request $request){
        $data = $request->all();

        $validator = $this->validation($data);
        if($validator->fails()){
            return $validator->errors()->add('hasError', true);
        }
        
        $data['photo'] = $this->addFile($request->photo);
        $data['degree'] = $this->addFile($request->degree);

        $user = $this->create($data);

        if($user == null){
            return ['error' => 'error in database please make sure you have internet or refresh the page!'];
        }

        return [
            'success' => 'Success! Doctor has been added!',
            'doctor' => json_encode($user),
        ];
    }

    public function deleteObject($objectName){ $this->storage->getBucket()->object($objectName)->delete(); }
    
    public function delete(Request $request){
        $id = $request->params['id'];
        $id_fb = $request->params['id_fb'];

        $this->firestore->database()->collection('Doctors')->document($id_fb)->delete();
        $doctor = User::find($id);

        $name = $doctor->name;
        $this->deleteObject($doctor->degree);
        $this->deleteObject($doctor->photoUrl);

        $doctor->delete();

        return ['success' => 'Dr. ' . $name .  ' has been Successfuly Deleted!'];
    }
}
