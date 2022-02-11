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

        return view('test')->with('page', $page);
    }
}
