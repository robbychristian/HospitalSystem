<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//firebase for construct()
use Kreait\Firebase\Contract\Firestore;

class ProfileController extends Controller
{
    public function __construct(Firestore $firestore)
    {
        $this->firestore = $firestore;
    }

    public function index()
    {
        $page = "Profile";
        $active = "profile";

        //get user details
        $documents = $this->firestore->database()->collection("Doctors");
        $query = $documents->where('id', '=', Auth::user()->id_fb);
        $doctorProfile = $query->documents();

        $details = [];

        foreach ($doctorProfile as $detail) {
            $data = $detail->data();
            $data['id'] = $detail->id();

            array_push(
                $details,
                $data
            );
        }

        return view('pages.profile')->with('page', $page)->with('active', $active)->with('details', $details);
    }
}
