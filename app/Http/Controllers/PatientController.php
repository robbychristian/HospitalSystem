<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Firestore;

use Carbon\Carbon;

class PatientController extends Controller
{
    public function __construct(Firestore $firestore){ 
        $this->firestore = $firestore; 
        $this->middleware('auth');
        $this->middleware('verified');
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
            $data['age'] = Carbon::parse($data['birthdate'])->diff(Carbon::now())->y;

            array_push(
                $patient,
                $data
            );
        }

        $patient = json_encode($patient);
        
        return view('pages.patient')->with('page', $page)->with('active', $active)->with('patient', $patient);
    }

    public function show(Request $request){
        dd($id);

    }
}
