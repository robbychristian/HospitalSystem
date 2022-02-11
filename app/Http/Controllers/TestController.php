<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Firestore;

class TestController extends Controller
{
    public function __construct(Firestore $firestore)
    {
        $this->firestore = $firestore;
    }

    public function index(){
        $data = $this->firestore->database()->collection('Test')->documents()->rows();
        
        $arr = array();

        foreach($data as $item){
            array_push($arr, $item->data());
        }

        $page="Test Page";
        $active="home";

        return view('test')->with('page', $page)->with('active', $active);
    }

    public function insert(){

        $firestore = $this->firestore->database()->collection('Patients')->newDocument();

        $firestore->set([
            'fname' => 'John',
            'lname' => 'Dacumos ',
            'type' => 'Pogi',
        ]);
    }
}
