<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

//firebase for construct()
use Kreait\Firebase\Contract\Firestore;

class CalendarController extends Controller
{
    public function __construct(Firestore $firestore)
    {
        $this->firestore = $firestore;
        $this->middleware('verified');
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

        //GET ALL EVENTS FOR ADMIN
        if (Auth::user()->isAdmin == 1) {
            $allAppointments = $this->firestore->database()->collection("AppointmentList")->documents()->rows();
            $appointments = [];

            foreach ($allAppointments as $appointment) {
                $data = $appointment->data();
                $data['id'] = $appointment->id();

                array_push(
                    $appointments,
                    $data
                );
            }
        } else { //GET ALL EVENTS FOR DOCTORS
            $allAppointments = $this->firestore->database()->collection("AppointmentList");
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
        }

        //GET ALL DOCTORS
        $allDoctors = $this->firestore->database()->collection("Doctors")->documents()->rows();
        $doctors = [];

        foreach ($allDoctors as $doctor) {
            $data = $doctor->data();
            $data['id'] = $doctor->id();

            array_push(
                $doctors,
                $data
            );
        }

        $appointments = json_encode($appointments);

        return view('pages.calendar')->with('page', $page)->with('active', $active)->with('patients', $patients)->with('appointments', $appointments)->with('doctors', $doctors);
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
        $doctor = $this->firestore->database()->collection("Doctors")->document(Auth::user()->id_fb)->snapshot()->data();
        $patient = $this->firestore->database()->collection("Patients")->document($request->patient)->snapshot()->data();

        $startDate = Carbon::create($request->date)->year . "-" . Carbon::create($request->date)->month . "-" . Carbon::create($request->date)->day . ' ' . $request->startTime;
        $endDate = Carbon::create($request->date)->year . "-" . Carbon::create($request->date)->month . "-" . Carbon::create($request->date)->day . ' ' . $request->endTime;
        $month = Carbon::create($request->date)->month;
        $day = Carbon::create($request->date)->isoFormat("ddd");

        $startTime = Carbon::create($startDate)->isoFormat("hh:mm");
        $endTime = Carbon::create($endDate)->isoFormat("hh:mm");

        //MAKE NEW APPOINTMENT
        $newAppointment = $this->firestore->database()->collection("AppointmentList")->newDocument();

        $newAppointment->set([
            'id' => $newAppointment->id(),
            'actualProblem' => $request->problem,
            'appointDate' => Carbon::parse($request->date)->format('m/d/y'),
            'appointState' => 'Teleconsultation',
            'appointStatus' => 'Pending',
            'appointTime' => '',
            'bookingDate' => Carbon::parse($request->date)->format('m/d/y'),
            'bookingSchedule' => $day . ' ' . $startTime . ' ' . $endTime,

            'drId' => Auth::user()->id_fb,
            'consultFee' => $doctor['consultFee'],
            'drClinic' => $doctor['clinicAddress'],
            'drDegree' => $doctor['degree'],
            'drEmail' => $doctor['email'],
            'drPhone' => $doctor['phone'],
            'drPhotoUrl' => $doctor['photoUrl'],
            'drfName' => $doctor['fname'],
            'specialization' => $doctor['specialization'],
            'drlName' => $doctor['lname'],

            'hospitalAddress' => '',
            'hospitalName' => '',
            'labRequest' => '1',

            'medicines' => '',
            'nextVisit' => '',

            'pAddress' => $patient['address'],
            'pBirthdate' => $patient['birthdate'],
            'pGender' => $patient['gender'],
            'pPhone' => $patient['phone'],
            'pPhotoUrl' => $patient['imageUrl'],
            'pfName' => $patient['fname'],
            'plName' => $patient['lname'],
            'pProblem' => $request->problem,
            'pId' => $request->patient,

            'prescribeDate' => '',
            'prescribeNo' => '',
            'prescribeState' => 'no',
            'rx' => '',
            'prescribeState' => '',

            'reviewComment' => '',
            'reviewDate' => '',
            'reviewStar' => '',
            'reviewTimeStamp' => '',
            'teleconsultFee' => '',
            'timeStamp' => '',
            'advice' => null,
        ]);

        //GET ALL EVENTS
        $allAppointments = $this->firestore->database()->collection("AppointmentList")->documents()->rows();

        $appointments = [];

        foreach ($allAppointments as $appointment) {
            $data = $appointment->data();
            $data['id'] = $appointment->id();

            array_push(
                $appointments,
                $data
            );
        }
        //GET ALL DOCTORS
        $allDoctors = $this->firestore->database()->collection("Doctors")->documents()->rows();
        $doctors = [];

        foreach ($allDoctors as $doctor) {
            $data = $doctor->data();
            $data['id'] = $doctor->id();

            array_push(
                $doctors,
                $data
            );
        }
        $appointments = json_encode($appointments);

        return redirect('/calendar')->with('Success', 'Stored')->with('appoinments', $appointments)->with('doctors', $doctors);
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
    public function destroy(Request $request)
    {
        $this->firestore->database()->collection('AppointmentList')->document($request->id)->delete();
    }
}
