<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Firestore;

use Carbon\Carbon;

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

        $patient = [];

        foreach ($documents as $document) {

            $data = $document->data();
            $data['id'] = $document->id();
            $data['joindate'] = Carbon::parse($data['joindate'])->format('F d, Y');


            array_push(
                $patient,
                $data
            );
        }

        $patient = json_encode($patient);
        
        return view('pages.patient')->with('page', $page)->with('active', $active)->with('patient', $patient);
    }
}
