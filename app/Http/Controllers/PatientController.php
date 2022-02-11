<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Firestore;

class PatientController extends Controller
{
    public function __construct(Firestore $firestore)
    {
        $this->firestore = $firestore;
    }

    public function index(){
        $page="Patient Records";
        $active="patient";

        $documents = $this->firestore->database()->collection('Patients')->documents()->rows();
        
        return view('pages.patient')->with('page', $page)->with('active', $active);
    }
}
