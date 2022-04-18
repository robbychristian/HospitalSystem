<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Firestore;
use Kreait\Firebase\Contract\Storage;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function __construct(Firestore $firestore)
    {
        $this->firestore = $firestore;
        $this->middleware('auth');
        $this->middleware('verified');
    }

    public function index()
    {
        $page = "Appointments";
        $active = "appointment";

        $documents = $this->firestore->database()->collection('AppointmentList');


        if (Auth::user()->isAdmin) {
            $documents = $documents->documents()->rows();
        } else {
            $documents = $documents->where('drId', '==', Auth::user()->id_fb)->documents()->rows();
        }

        $appointment = [];

        foreach ($documents as $document) {
            $data = $document->data();

            $data['id'] = $document->id();
            $data['dName'] = $data['drfName'] . ' ' . $data['drlName'];
            $data['pName'] = $data['pfName'] . ' ' . $data['plName'];

            if ($data['prescribeState'] == "no") {
                if ($data['appointStatus'] == "Pending") {
                    array_unshift(
                        $appointment,
                        $data
                    );
                } else {
                    array_push(
                        $appointment,
                        $data
                    );
                }
            }
        }
        
        $documents = $this->firestore->database()->collection('Hospitals')->documents()->rows();

        $hospitals = [];

        foreach ($documents as $document) {
            $data = $document->data();
            $data['id'] = $document->id();
            
            array_push(
                $hospitals,
                $data
            );
        }

        $appointment = json_encode($appointment);
        $hospitals = json_encode($hospitals);


        return view('pages.appointment')->with('page', $page)->with('active', $active)->with('appointment', $appointment)->with('hospitals', $hospitals);
    }

    public function status(Request $request)
    {

        try {
            $appointment = $this->firestore->database()->collection('AppointmentList')->document($request->id);
            $appointment->update([
                ['path' => 'appointStatus', 'value' => $request->status],
            ]);
        } catch (\Exception $e) {
            return ['hasError' => true];
        }
        return  ['hasError' => false];
    }

    public function lab(Request $request)
    {

        $data = $request->all();

        $updateArray = [
            ['path' => 'labRequest', 'value' => $data['data']],
        ];

        $this->firestore->database()->collection('AppointmentList')->document($data['id'])->update($updateArray);

        return response()->json([
            'hasError' => false,
        ]);
    }
}
