<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Kreait\Firebase\Contract\Firestore;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use Kreait\Firebase\Contract\Storage;

class DashboardController extends Controller
{

    public function __construct(Firestore $firestore,  Storage $storage)
    {
        $this->firestore = $firestore;
        $this->storage = $storage;
        //$this->middleware('auth');
        //$this->middleware('verified');
    }

    public function index()
    {

        $page = "Dashboard";
        $active = "home";

        // $documents = $this->firestore->database()->collection('Doctors')->documents()->rows();
        // $doctors = [];
        // foreach ($documents as $document) {

        //     $data = $document->data();

        //     if(!$data['isAdmin']){
        //         $data['id'] = $document->id();
        //         $data['joindate'] = Carbon::parse($data['joindate'])->format('F d, Y');

        //         array_push( $doctors, $data );
        //     }
        // }

        $doctors = [];
        // ->where('doctor_id', '==', Auth::user()->id_fb)
        $documents = $this->firestore->database()->collection("Announcements");

        if (Auth::user()->isAdmin) {
            $documents = $documents->documents()->rows();
            $doctors = User::where('isAdmin', false)->get();
        } else {
            $documents = $documents->where('author_id', '==', Auth::user()->id_fb)->documents()->rows();
        }

        $announcements = [];
        foreach ($documents as $document) {
            $data = $document->data();
            $data['id'] = $document->id();
            $data['date'] = Carbon::parse($data['date'])->format('F d, Y');

            array_push($announcements, $data);
        }

        $doctors = json_encode($doctors);
        $announcements = json_encode($announcements);


        return view('pages.dashboard')->with('page', $page)->with('active', $active)
            ->with('doctors', $doctors)->with('announcements', $announcements);
    }
}
