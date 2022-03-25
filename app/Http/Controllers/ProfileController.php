<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Firebase;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{

    public function __construct(){
        $this->firestore = Firebase::firestore();
        $this->storage = Firebase::storage();
        $this->auth = Firebase::auth();
        $this->middleware('auth')->except('update');
        $this->middleware('verified')->except('update');
    }

    public function index(){ 

        $page = "Profile";
        $active = "profile";

        
        //get user details
        $documents = $this->firestore->database()->collection("Doctors");
        $query = $documents->where('id', '=', Auth::user()->id_fb);
        $doctorProfile = $query->documents();

        $details = [];

        foreach ($doctorProfile as $detail) {
            $data = $detail->data();
            $data['id'] = $detail->id();

            if( $data['otherSpecialization'] == null)
                $data['otherSpecialization'] = [];

            $sub = substr($data['photoUrl'], 0, 5);
            if($data['photoUrl'] != '' && $data['photoUrl'] != null && $sub != 'https'){
                
                $data['photoUrl'] = $this->signedUrl($data['photoUrl']);
            }

            
            if( $data['photoUrl'] == '' || $data['photoUrl'] == null){
                $data['photoUrl'] = '/img/avatar.png';
            }

            array_push(
                $details,
                $data
            );
        }

        return view('pages.profile')->with('page', $page)->with('active', $active)->with('details', $details);
    }

    public function deleteObject($objectName){ $this->storage->getBucket()->object($objectName)->delete(); }

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

    public function signedUrl($image){
        try{
            $object = $this->storage->getBucket()->object($image);

            if($object->exists()){
                $image = $object->signedUrl(now()->addDays(1));
            }
        }
        catch (Throwable $e){
        }
        
        return $image;
    }

    public function validateClinic(array $data){
        return Validator::make($data, [
            'clinicAddress' => ['required', 'string', 'max:255'],
            'consultFee' => ['required', 'integer',],
            'teleconsultFee' => ['required', 'integer',],
        ]);
    }

    public function validatePersonal( array $data, $withEmail){
        if($withEmail){
            return Validator::make($data, [
                'firstName' => ['required', 'regex:/^[\pL\s\-]+$/u', 'max:255', ],
                'lastName' => ['required', 'regex:/^[\pL\s\-]+$/u', 'max:255', ],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'degree' => ['required', 'string', 'max:255', ],
                'photo' => ['nullable ', 'image', 'mimes:jpg,png,jpeg', ],
                'phone' => ['required', 'numeric', 'digits_between:9,11', ],
                'password' => ['nullable', 'string', 'min:8'],
            ]);
        }

        return Validator::make($data, [
            'firstName' => ['required', 'regex:/^[\pL\s\-]+$/u', 'max:255', ],
            'lastName' => ['required', 'regex:/^[\pL\s\-]+$/u', 'max:255', ],
            'degree' => ['required', 'string', 'max:255', ],
            'photo' => ['nullable ', 'image', 'mimes:jpg,png,jpeg', ],
            'phone' => ['required', 'numeric', 'digits_between:9,11', ],
            'password' => ['nullable', 'string', 'min:8'],
        ]);
    }

    public function updateClinic($data, $doctor){

        $updateArray = [
            ['path' => 'clinicAddress', 'value' => $data->clinicAddress],
            ['path' => 'consultFee', 'value' => $data->consultFee],
            ['path' => 'provideTeleService', 'value' => $data->provideTeleService],
            ['path' => 'teleconsultFee', 'value' => $data->teleconsultFee],
            ['path' => 'teleSun', 'value' => $data->teleSun],
            ['path' => 'teleMon', 'value' => $data->teleMon],
            ['path' => 'teleTue', 'value' => $data->teleTue, ],
            ['path' => 'teleWed', 'value' => $data->teleWed ],
            ['path' => 'teleThurs', 'value' => $data->teleThurs],
            ['path' => 'teleFri', 'value' =>  $data->teleFri],
            ['path' => 'teleSat', 'value' => $data->teleSat],
        ];

        $this->firestore->database()->collection('Doctors')->document($doctor->id_fb)->update($updateArray);

        $doctor->clinicAddress = $data->clinicAddress;
        $doctor->consultFee = $data->consultFee;
        $doctor->teleconsultFee = $data->teleconsultFee;
        $doctor->provideTeleService = $data->provideTeleService;
        $doctor->save();

        return $data;
    }

    public function updatePersonal($data, $request, $withEmail, $doctor){
        $name = $data->fname . " " . $data->lname;

        $updateArray = [
            ['path' => 'fname', 'value' => $data->fname],
            ['path' => 'lname', 'value' => $data->lname],
            ['path' => 'phone', 'value' => $data->phone],
            ['path' => 'gender', 'value' => $data->gender],
            ['path' => 'about', 'value' => $data->about, ],
            ['path' => 'email', 'value' => $data->email],
            ['path' => 'photoUrl', 'value' => $data->image ],
            ['path' => 'degree', 'value' =>  $data->degree],
            ['path' => 'name', 'value' => $name],
            ['path' => 'otherSpecialization', 'value' => $data->otherSpecialization],
            ['path' => 'specialization', 'value' => $data->specialization],
        ];
        
        if($withEmail){ 
            $this->auth->changeUserEmail($doctor->id_fb,  $data->email);
            // $doctor->isVerified = false;
            // array_push($updateArray, ['path' => 'isVerified', 'value' => 'NO']);
        }
        
        $this->firestore->database()->collection('Doctors')->document($doctor->id_fb)->update($updateArray);

        $doctor->name =  $name;
        $doctor->fname = $data->fname;
        $doctor->lname = $data->lname;
        $doctor->phone = $data->phone;
        $doctor->email = $data->email;
        $doctor->about = $data->about;
        $doctor->gender = $data->gender == 'Male' ? 1 : 0;
        $doctor->degree = $data->degree;
        $doctor->photoUrl = $data->image;
        $doctor->specialization = $data->specialization;
        $doctor->about = $data->about;

        $doctor->save();

        return $data;
    }

    public function personal(Request $request){
        $data = json_decode($request->all()['data']);
        
        $validate = [
            'photo' => $request->image == 'null' ? '' : $request->image,
            'firstName' => $data->fname,
            'lastName' => $data->lname,
            'degree' => $data->degree,
            'phone' => $data->phone,
            'email' => $data->email,
        ];

        $withEmail = $data->email != Auth::user()->email;
        $doctor = $request->user();

        $validator = $this->validatePersonal($validate, $withEmail);

        if($validator->fails()){
            return $validator->errors()->add('hasError', true);
        }

        if($request->hasFile('image')){
            $data->image = $this->addFile($request->image, $doctor->id_fb);

            $go = $doctor->photoUrl == null || $doctor->photoUrl == '' ? false : true;
            if($go) $this->deleteObject($doctor->photoUrl);
        }
        else $data->image = $doctor->photoUrl;

        $this->updatePersonal($data, $request, $withEmail, $doctor);

        return response()->json([
            'hasError' => false,
            'text' => 'Personal Information has been updated!'
        ]);
    }

    public function clinic(Request $request){
        $data = json_decode($request->all()['data']);

        $validate =[
            'clinicAddress' => $data->clinicAddress,
            'consultFee' => $data->consultFee,
            'teleconsultFee' => $data->teleconsultFee,
        ];

        $doctor = $request->user();

        $validator = $this->validateClinic($validate);

        if($validator->fails()){
            return $validator->errors()->add('hasError', true);
        }

        if( $request->go == 'false' ){
            return response()->json([
                'hasError' => false,
            ]);
        }

        $this->updateClinic($data, $doctor);
        
        return response()->json([
            'hasError' => false,
            'text' => 'Hospital Information has been updated!'
        ]);
    }

    public function password(Request $request){

        $data = $request->all();
        
        $validate =[
            'password' => $data['password'],
            'password_confirmation' => $data['confirmPassword'],
        ];

        $validator = Validator::make($validate, [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if($validator->fails()){
            return $validator->errors()->add('hasError', true);
        }

        $doctor = $request->user();

        $updateArray = [
            ['path' => 'password', 'value' => $data['password']],
        ];

        $this->firestore->database()->collection('Doctors')->document($doctor->id_fb)->update($updateArray);
        
        $doctor->password = Hash::make($data['password']);
        $doctor->save();
        $this->auth->changeUserPassword($doctor->id_fb, $data['password']);

        return response()->json([
            'hasError' => false,
            'text' => 'Passwords has been updated!'
        ]);
    }
    
    public function update($id_fb, $pass){
        
        $doctor = User::where('id_fb', $id_fb)->first();
        
        $data = $this->firestore->database()->collection('Doctors')->document($id_fb)->snapshot()->data();
        
        $doctor->name = $data['fname'] . ' ' . $data['lname'];
        $doctor->fname = $data['fname'];
        $doctor->lname = $data['lname'];
        $doctor->phone = $data['phone'];
        $doctor->email = $data['email'];
        $doctor->about = $data['about'];
        $doctor->clinicAddress = $data['clinicAddress'];
        $doctor->joinDate = $data['joinDate'];
        $doctor->gender = $data['gender'] == "Female" ? 0 : 1;
        $doctor->specialization = $data['specialization'];
        $doctor->degree = $data['degree'];
        $doctor->consultFee = $data['consultFee'] == '' ? 0 : $data['consultFee'];
        $doctor->teleconsultFee = $data['consultFee'] == '' ? 0 : $data['consultFee'];
        $doctor->photoUrl = $data['photoUrl'];
        $doctor->provideTeleService = $data['provideTeleService'];
        
        $doctor->password = Hash::make($pass);
        $this->auth->changeUserPassword($id_fb, $pass);

        $doctor->save();
        
        return response()->json([
            'text' => "DONE",
        ]);
    }


}
