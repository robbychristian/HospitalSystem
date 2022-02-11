<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Firestore;
use Carbon\Carbon;


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
}
