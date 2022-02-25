<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Kreait\Firebase\Contract\Firestore;
use Kreait\Firebase\Contract\Storage;
use Kreait\Firebase\Contract\Auth;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DoctorController extends Controller
{
    public function __construct(Firestore $firestore, Storage $storage, Auth $auth){ 
        $this->firestore = $firestore; 
        $this->storage = $storage;
        $this->auth = $auth;
        $this->middleware('auth');
        $this->middleware('admin');
        $this->middleware('verified');
    }
    
    public function deleteObject($objectName){ $this->storage->getBucket()->object($objectName)->delete(); }

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

    public function validation( array $data, $withEmail){
        if($withEmail){
            return Validator::make($data, [
                'clinicAddress' => ['required', 'string', 'max:255'],
                'consultFee' => ['required', 'integer',],
                'specialization' => ['required', 'string', 'max:255', ],
                'firstName' => ['required', 'regex:/^[\pL\s\-]+$/u', 'max:255', ],
                'lastName' => ['required', 'regex:/^[\pL\s\-]+$/u', 'max:255', ],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'degree' => ['nullable ', 'file', 'mimes:pptx,docx,doc,pdf', ],
                'photo' => ['nullable ', 'image', 'mimes:jpg,png,jpeg', ],
                'phone' => ['required', 'numeric', 'digits_between:9,11', ],
                // 'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
        }

        return Validator::make($data, [
            'clinicAddress' => ['required', 'string', 'max:255'],
            'consultFee' => ['required', 'integer',],
            'specialization' => ['required', 'string', 'max:255', ],
            'firstName' => ['required', 'regex:/^[\pL\s\-]+$/u', 'max:255', ],
            'lastName' => ['required', 'regex:/^[\pL\s\-]+$/u', 'max:255', ],
            'degree' => ['nullable ', 'file', 'mimes:pptx,docx,doc,pdf', ],
            'photo' => ['nullable ', 'image', 'mimes:jpg,png,jpeg', ],
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

    public function update(Request $request){
        $data = $request->all();
        $validator = $this->validation($data, $data['withEmail']);
        if($validator->fails()){
            return $validator->errors()->add('hasError', true);
        }

        $doctor = User::find($data['id']);
        $data['gender'] = $data['gender'] == 'true' ? 1 : 0;

        if($data['photo'] != null || $data['photo'] != ''){
            $data['photo'] = $this->addFile($request->photo);
            $this->deleteObject($doctor->photoUrl);
        }
        else{
            $data['photo'] = $doctor->photoUrl;
        }
        
        if($data['degree'] != null || $data['degree'] != ''){
            $this->deleteObject($doctor->degree);
            $data['degree'] = $this->addFile($request->degree);
        }
        else{
            $data['degree'] = $doctor->degree;
        }

        $name = $data['firstName'] . " " . $data['lastName'];

        $updateArray = [
            ['path' => 'fName', 'value' => $data['firstName']],
            ['path' => 'lName', 'value' => $data['lastName']],
            ['path' => 'phone', 'value' => $data['phone']],
            ['path' => 'gender', 'value' => $data['gender'] == 'true' ? true : false],
            ['path' => 'about', 'value' => $data['about'], ],
            ['path' => 'email', 'value' => $data['email']],
            ['path' => 'clinicAddress', 'value' => $data['clinicAddress'] ],
            ['path' => 'consultFee', 'value' => $data['consultFee'] ],
            ['path' => 'specialization', 'value' => $data['specialization']],
            ['path' => 'photoUrl', 'value' => $data['photo'] ],
            ['path' => 'degree', 'value' =>  $data['degree']],
            ['path' => 'name', 'value' => $name],
        ];
        
        if($data['withEmail']){ 
            $doctor->isVerified = false;
            array_push($updateArray, ['path' => 'isVerified', 'value' => 0]);
        }
        
        $this->firestore->database()->collection('Doctors')->document($doctor->id_fb)->update($updateArray);

        $doctor->name =  $name;
        $doctor->fname = $data['firstName'];
        $doctor->lname = $data['lastName'];
        $doctor->phone = $data['phone'];
        $doctor->email = $data['email'];
        $doctor->about = $data['about'];
        $doctor->clinicAddress = $data['clinicAddress'];
        $doctor->gender = $data['gender'];
        $doctor->specialization = $data['specialization'];
        $doctor->degree = $data['degree'];
        $doctor->consultFee = $data['consultFee'];
        $doctor->photoUrl = $data['photo'];

        $doctor->save();


        return [
            'success' => 'Success! Doctor has been added!',
            'doctor' => json_encode($doctor),
        ];

    }

    public function add(Request $request){
        $data = $request->all();

        $validator = $this->validation($data, true);
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
