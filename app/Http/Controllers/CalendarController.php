<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//firebase for construct()
use Kreait\Firebase\Contract\Firestore;

class CalendarController extends Controller
{
    public function __construct(Firestore $firestore)
    {
        $this->firestore = $firestore;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page = "Calendar";
        $active = "calendar";
        //GET ALL PATIENTS
        $documents = $this->firestore->database()->collection("Patients")->documents()->rows();

        $patients = [];

        foreach ($documents as $document) {
            $data = $document->data();
            $data['id'] = $document->id();

            array_push($patients, $data);
        }

        $patients = json_encode($patients);

        //GET ALL EVENTS
        $allAppointments = $this->firestore->database()->collection("Appointments");
        $query = $allAppointments->where('doctor_id', '==', Auth::user()->id_fb);
        $doctorAppointments = $query->documents();

        $appointments = [];

        foreach ($doctorAppointments as $appointment) {
            $data = $appointment->data();
            $data['id'] = $appointment->id();

            array_push(
                $appointments,
                $data
            );
        }

        $appointments = json_encode($appointments);

        return view('pages.calendar')->with('page', $page)->with('active', $active)->with('patients', $patients)->with('appointments', $appointments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //MAKE NEW APPOINTMENT
        $newAppointment = $this->firestore->database()->collection("Appointments")->newDocument();
        $newAppointment->set([
            'doctor_id' => Auth::user()->id_fb,
            'patient_id' => $request->patient,
            'title' => $request->title,
            'problem' => $request->problem,
            'start' => $request->startTime,
            'end' => $request->endTime
        ]);
        //GET ALL EVENTS
        $allAppointments = $this->firestore->database()->collection("Appointments")->documents()->rows();

        $appointments = [];

        foreach ($allAppointments as $appointment) {
            $data = $appointment->data();
            $data['id'] = $appointment->id();

            array_push(
                $appointments,
                $data
            );
        }

        $appointments = json_encode($appointments);

        return redirect('/test/calendar')->with('Success', 'Stored')->with('appoinments', $appointments);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
