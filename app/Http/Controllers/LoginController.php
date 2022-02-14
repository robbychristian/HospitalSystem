<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//firebase for index()
use Firebase;
//firebase for construct()
use Kreait\Firebase\Contract\Firestore;

class LoginController extends Controller
{
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
            array_push($users, $data->data());
        }

        foreach ($users as $user) {
            if ($user['email'] != $request->email) {
                return redirect('/')->with("Error", "Credentials does not match anything in the records");
            } else {
                return redirect('test/');
            }
        }
    }

    public function DebuggerPage()
    {
        return view('debugger');
    }
}
