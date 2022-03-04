<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use Kreait\Firebase\Contract\Firestore;
use Kreait\Firebase\Contract\Storage;
// use Kreait\Firebase\Contract\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;


class TestController extends Controller
{
    
    public function __construct(Firestore $firestore, Storage $storage){ 
        $this->firestore = $firestore; 
        $this->storage = $storage;
        // $this->auth = $auth;  Auth $auth
        // $this->middleware('auth');
    }

    public function index(){
        $page="Test";
        $active="test";

        return view('test')->with('page', $page)->with('active', $active);

        // http://storage.googleapis.com/[BUCKET_NAME]/[OBJECT_NAME]
        // $nameBucket = $this->storage->getBucket()->name();
        // $object = $this->storage->getBucket()->object(Auth::user()->photoUrl);

        // dd($object->signedUrl(now()->addDays(1)));
        // dd(
        //     "http://storage.googleapis.com/" . $nameBucket .  $object->identity()['object']
        // );
        
        // $user = $this->firestore->database()->collection('Doctors')->document('d00af10222d741648e2c');

        // $user->set([
        //     'id' => $user->id(),
        //     'fname' => $data->fname,
        //     'lname' => $data->lname,
        //     'phone' => $data->phone,
        //     'email' => $data->email,
        //     'about' => $data->about,
        //     'clinicAddress' => $data->clinicAddress,
        //     'joinDate' => now()->format('d-m-Y'),
        //     'isVerified' => '1',
        //     'isAdmin' => '1',
        //     'gender' => '',
        //     'specialization' => $data->specialization,
        //     'otherSpecialization' => null,
        //     'consultFee' => (string)$data->consultFee,
        //     'photoUrl' => $data->photoUrl,
        //     'degree' => $data->degree,
        //     'password' => 'eternals',
        //     'totalPrescribe' => '0',
        //     'totalEarnings' => '0',
        //     'provideTeleService' => true,
        //     'teleconsultFee' => '0',
        //     'teleMon' => null,
        //     'teleSat' => null,
        //     'teleSun' => null,
        //     'teleThurs' => null,
        //     'teleTue' => null,
        //     'teleWed' => null,
        //     'teleFri' => null,
        // ]);
        
        // $userProperties = [
        //     'email' => 'admin@teledoc.com',
        //     'password' => "eternals",
        //     'uid' => $user->id(),
        // ];
        
        // $this->auth->createUser($userProperties);
        
    }

    public function insert(){
        
        $data = User::find(4);
        dd($data);
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

        
    }

    public function calendar(){
        $page="Calendar";
        $active="calendar";

        return view('pages.calendar')->with('page', $page)->with('active', $active);
    }

    public function inquiry(){
        $page="Inquiry";
        $active="inquiry";

        return view('pages.inquiry')->with('page', $page)->with('active', $active);
    }
    
    // Create user in auth
    // $userProperties = [
    //     'email' => 'user@example.com',
    //     'password' => 'secretPassword',
    //     'uid' => '861ea705c8fe4535be86',
    // ];
    
    // $createdUser = $this->auth->createUser($userProperties);
}
