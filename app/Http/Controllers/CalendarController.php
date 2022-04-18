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
        $this->middleware('verified')->except('destroy');
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
        $user = [];

        foreach ($documents as $document) {
            $data = $document->data();
            $data['id'] = $document->id();

            array_push($patients, $data);
        }

        $patients = json_encode($patients);
        $doctors = [];

        //GET ALL EVENTS FOR ADMIN AND DOCTORS
        if (Auth::user()->isAdmin == 1) {
            $allAppointments = $this->firestore->database()->collection("AppointmentList")->documents()->rows();
            $appointments = [];

            foreach ($allAppointments as $appointment) {
                $data = $appointment->data();
                $data['id'] = $appointment->id();

                if ($data['appointStatus'] == "Approved") {
                    array_push(
                        $appointments,
                        $data
                    );
                }
            }

            $allDoctors = $this->firestore->database()->collection("Doctors")->documents()->rows();

            foreach ($allDoctors as $doctor) {
                $data = $doctor->data();
                $data['id'] = $doctor->id();

                $hospitals = $this->firestore->database()->collection("Hospitals")->where('doctorId', '==', $data['id'])->documents()->rows();
                $data['hospital'] = [];

                foreach ($hospitals as $hospital) {
                    $hospitalData = $hospital->data();
                    $hospitalData['id'] = $hospital->id();

                    array_push(
                        $data['hospital'],
                        $hospitalData
                    );
                }

                array_push(
                    $doctors,
                    $data
                );
            }
        } else { //GET ALL EVENTS FOR DOCTORS
            $allAppointments = $this->firestore->database()->collection("AppointmentList");
            // $doctorData = $this->firestore->database()->collection("Doctors");
            $query = $allAppointments->where('drId', '==', Auth::user()->id_fb);
            // $drQuery = $doctorData->where('id', '==', Auth::user()->id_fb);
            $doctorAppointments = $query->documents();
            // $getDrData = $drQuery->documents();

            $user = $this->firestore->database()->collection("Doctors")->document(Auth::user()->id_fb)->snapshot()->data();

            $hospitals = $this->firestore->database()->collection("Hospitals")->where('doctorId', '==', Auth::user()->id_fb)->documents()->rows();
            $user['hospital'] = [];

            foreach ($hospitals as $hospital) {
                $hospitalData = $hospital->data();
                $hospitalData['id'] = $hospital->id();

                array_push(
                    $user['hospital'],
                    $hospitalData
                );
            }

            $appointments = [];
            // $doctorsDatas = [];

            foreach ($doctorAppointments as $appointment) {
                $data = $appointment->data();
                $data['id'] = $appointment->id();


                if ($data['appointStatus'] == "Approved") {
                    array_push(
                        $appointments,
                        $data
                    );
                }
            }

            // foreach ($getDrData as $drData) {
            //     $data = $drData->data();
            //     $data['id'] = $drData->id();

            //     array_push(
            //         $doctorsDatas,
            //         $data
            //     );
            // }
        }

        $appointments = json_encode($appointments);
        $doctors = json_encode($doctors);
        $user = json_encode($user);
        // $doctorsDatas = json_encode($doctorsDatas);

        return view('pages.calendar')->with('page', $page)->with('active', $active)->with('patients', $patients)
            ->with('appointments', $appointments)->with('doctors', $doctors)->with('user', $user);
        // ->with("drData", $doctorsDatas);
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
        // dd($request->all());
        $doctor = '';

        if (Auth::user()->isAdmin) {
            $doctor = $this->firestore->database()->collection("Doctors")->document($request->doctor)->snapshot()->data();
        } else {
            $doctor = $this->firestore->database()->collection("Doctors")->document(Auth::user()->id_fb)->snapshot()->data();
        }
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
            'appointDate' => Carbon::parse($request->date)->format('m/d/Y'),
            'appointState' => $request->appointState,
            'appointStatus' => 'Pending',
            'appointTime' => '',
            'bookingDate' => now()->format('m/d/Y'),
            'bookingSchedule' => $request->timeSlot,

            'drId' => $doctor['id'],
            'consultFee' => $request->appointState == 'Hospital' ? [$doctor['consultFee']] : [] ,
            'drClinic' => $doctor['clinicAddress'],
            'drDegree' => $doctor['degree'],
            'drEmail' => $doctor['email'],
            'drPhone' => $doctor['phone'],
            'drPhotoUrl' => $doctor['photoUrl'],
            'drfName' => $doctor['fname'],
            'specialization' => $doctor['specialization'],
            'drlName' => $doctor['lname'],
            'signature' => $doctor['signature'],

            'hospitalAddress' => $request->hospitalAddress,
            'hospitalName' => $request->hospitalName,
            'labRequest' => '1',

            'medicines' => '',
            'nextVisit' => '',

            'pAddress' => $patient['address'],
            'pBirthdate' => $patient['birthdate'],
            'pGender' => $patient['gender'],
            'pPhone' => $patient['phone'],
            'pPhotoUrl' => $patient['imageUrl'], //change to imageUrl in PRODUCTION //change to photoUrl in DEVELOPMENT
            'pfName' => $patient['fname'],
            'plName' => $patient['lname'],
            'pProblem' => $request->problem,
            'pId' => $request->patient,

            'prescribeDate' => '',
            'prescribeNo' => '',
            'prescribeState' => 'no',
            'rx' => '',

            'reviewComment' => '',
            'reviewDate' => '',
            'reviewStar' => '',
            'reviewTimeStamp' => '',
            'teleconsultFee' => $request->appointState == 'Hospital' ? [] : [$doctor['teleconsultFee']],
            'timeStamp' => '',
            'advice' => null,

            //NEW
            'status' => 'Pending',
            'lic' => $doctor['lic'],
            's2' => $doctor['s2'],
            'ptr' => $doctor['ptr'],
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
        $doctors = json_encode($doctors);

        return redirect('/calendar')->with('Success', 'Stored');
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

    public function setOnGoing($id)
    {
        try {
            $appointment = $this->firestore->database()->collection('AppointmentList')->document($id);
            $appointment->update([
                ['path' => 'status', 'value' => 'On Going'],
            ]);
        } catch (\Exception $e) {
            return $e;
        }
        return redirect('/calendar');
    }

    public function setLate($id)
    {
        try {
            $appointment = $this->firestore->database()->collection('AppointmentList')->document($id);
            $data = $appointment->snapshot()->data();
            $currQueue = $data['prescribeNo'];

            $appointDate = $data['appointDate'];
            $drId = $data['drId'];

            $appointments =  $this->firestore->database()->collection('AppointmentList')->where('drId', '==', $drId)->documents()->rows();
            
            foreach($appointments as $appoint){
                $appointData = $appoint->data();

                if($appointData['appointDate'] == $appointDate && $appointData['appointState'] == 'Hospital' && $appointData['appointStatus'] == 'Approved' && $appointData['prescribeNo'] == $currQueue + 1){
                    $this->firestore->database()->collection('AppointmentList')->document($appoint->id())->update([
                        ['path' => 'prescribeNo', 'value' => (string)$currQueue],
                    ]);
                }
            }

            $appointment->update([
                ['path' => 'status', 'value' => 'Pending'],
                ['path' => 'prescribeNo', 'value' => (string)($currQueue + 1)],
            ]);

        } catch (\Exception $e) {
            return $e;
        }
        return redirect('/calendar');
    }

    public function setEarly($id)
    {
        try {
            $appointment = $this->firestore->database()->collection('AppointmentList')->document($id);
            $appointment->update([
                ['path' => 'status', 'value' => 'Finished Early'],
            ]);
        } catch (\Exception $e) {
            return $e;
        }
        return redirect('/calendar');
    }

    public function cancel($id)
    {
        try {
            $appointment = $this->firestore->database()->collection("AppointmentList")->document($id);
            $appointment->update([
                ['path' => 'appointStatus', 'value' => 'Cancelled'],
            ]);
        } catch (\Exception $e) {
            return $e;
        }

        return redirect('/calendar');
    }
}
