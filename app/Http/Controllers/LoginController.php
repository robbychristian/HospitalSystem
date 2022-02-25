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
    public function __construct()
    {
        $this->middleware('guest')->except('offline');
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
        $credentials = [
            'email' => $request->email,
            'password' => $request->pass,
        ];

        if (Auth::guard()->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/dashboard');
        }

        return redirect('/')->with("Error", "Credentials does not match anything in the records");
    }

    public function DebuggerPage()
    {
        return view('debugger');
    }

    public function offline(Request $request)
    {
        Auth::guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
