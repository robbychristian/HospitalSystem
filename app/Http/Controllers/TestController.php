<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use Kreait\Firebase\Contract\Firestore;
use Kreait\Firebase\Contract\Storage;
use Kreait\Firebase\Contract\Auth;


class TestController extends Controller
{
    
    public function __construct(Firestore $firestore, Storage $storage, Auth $auth){ 
        $this->firestore = $firestore; 
        $this->storage = $storage;
        $this->auth = $auth;
        // $this->middleware('auth');
    }

    public function index(){
        
        $page="Test page";
        $active="";

        return view('test')->with('page', $page)->with('active', $active);
        
    }

    public function insert(){

        $firestore = $this->firestore->database()->collection('Patients')->newDocument();

        $firestore->set([
            'fname' => 'Rhasta',
            'lname' => 'Man',
            'phone' => '09999999',
            'joindate' => now(),
            'gender' => 0,
            'nationality' => 'Philippines',
            'email' => 'some@gmail.com',
            'address' => 'Underground',
            'blood type' => 'B',
            'birtdate' => Carbon::parse('february 19, 2000')->format('F d, Y'),
            'takenTableService' => null,
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
