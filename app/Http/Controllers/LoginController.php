<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//firebase for index()
use Firebase;
//firebase for construct()
use Kreait\Firebase\Contract\Firestore;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct(){ 
        $this->middleware('guest');
    }


    public function index()
    {
        $data = Firebase::firestore()->database()->collection('Test')->documents();
        dd($data);
    }

    //LOGIN SCREEN ONLY
    public function LoginScreen()
    {
        $firestore = app('firebase.firestore');
        $database = $firestore->database();
        $datas = $database->collection('Doctors')->documents()->rows(); //array
        $r = [];
        foreach ($datas as $data) {
            array_push($r, $data->data());
        }
        $data = json_encode($r);

        $page = "Home Page";
        $active = "home";

        return view('pages.login')->with('data', $data)->with('page', $page)->with('active', $active);
    }

    //LOGIN FUNCTION
    public function Login(Request $request)
    {
        $firestore = app('firebase.firestore');
        $database = $firestore->database();
        $datas = $database->collection('Doctors')->documents()->rows(); //array
        $users = [];
        foreach ($datas as $data) {
            $user = $data->data();
            $user['id'] = $data->id();
            array_push($users, $user);
        }

        // yung unang user lang yung macoconsider ma login kaya dapat sa labas yung redirect '/' with error
        // foreach ($users as $user) {
        //     if ($user['email'] != $request->email) {
        //         return redirect('/')->with("Error", "Credentials does not match anything in the records");
        //     } else {
                
        //         return redirect('test/');
        //     }
        // }

        // ito dapat

        foreach ($users as $user) {
            if ($user['email'] == $request->email && $user['password'] == $request->pass) {

                $data = $this->create($user);

                Auth::guard()->login($data);

                return redirect('test/');
            }
        }
        return redirect('/')->with("Error", "Credentials does not match anything in the records");
    }

    public function DebuggerPage()
    {
        return view('debugger');
    }

    public function create($user){
        return User::create([
            'id_fb' => $user['id'],
            'fname' => $user['fName'],
            'lname' => $user['lName'],
            'phone' => $user['phone'],
            'email' => $user['email'],
            'about' => $user['about'],
            'clinicAddress' => $user['clinicAddress'],
            'joinDate' => $user['joinDate'],
            'isVerified' => $user['isVerified'],
            'gender' => $user['gender'] == "" ? null : $user['gender'],
            'specialization' => $user['specialization'],
            'degree' => $user['degree'],
            'consultFee' => $user['consultFee'],
            'teleconsultFee' => $user['teleconsultFee'],
            'isAdmin' => $user['isAdmin'],
            'photoUrl' => $user['photoUrl'],
            'totalPrescribe' => $user['totalPrescribe'],
            'totalEarnings' => $user['totalEarnings'],
            'provideTeleService' => $user['provideTeleService'],
            'password' => Hash::make($user['password']),
        ]);
    }
}
