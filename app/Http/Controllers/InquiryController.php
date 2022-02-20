<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Firestore;
use Carbon\Carbon;

class InquiryController extends Controller
{
    public function __construct(Firestore $firestore){ 
        $this->firestore = $firestore; 
        $this->middleware('auth');
    }

    public function index(){
        $page="Laboratory Request & Prescription";
        $active="inquiry";


        // $documents = $this->firestore->database()->collection('Doctors')->document(<doctor id>)->
        // ->collection('Patients')->documents()->rows();
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

        return view('pages.inquiry')->with('page', $page)->with('active', $active)->with('patient', $patient);
    }
}
