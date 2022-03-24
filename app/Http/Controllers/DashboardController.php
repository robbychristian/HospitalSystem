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
        $this->middleware('auth');
        $this->middleware('verified');
    }

    public function index()
    {

        $page = "Dashboard";
        $active = "home";
        
        $doctors = [];
        $reviews = [];
        // ->where('doctor_id', '==', Auth::user()->id_fb)

        if (Auth::user()->isAdmin) {
            $doctors = User::where('isAdmin', false)->get();
        }

        if(!Auth::user()->isAdmin){
            $documents = $this->firestore->database()->collection("AppointmentList")->where('drId', '==', Auth::user()->id_fb)->documents()->rows();
            
            foreach ($documents as $document) {
                $data = $document->data();
                
                if($data['reviewStar'] != null && $data['reviewStar'] != ''){
                    $review = [
                        'star' => $data['reviewStar'],
                        'date' => $data['reviewDate'],
                        'comment' => $data['reviewComment'],
                        'img' => $data['pPhotoUrl'],
                        'name' => $data['pfName'] . ' ' . $data['plName'],
                    ];
        
                    array_push($reviews, $review);
                }
                    
            }
        }
        
        $doctors = json_encode($doctors);
        $reviews = json_encode($reviews);


        return view('pages.dashboard')->with('page', $page)->with('active', $active)
            ->with('doctors', $doctors)->with('reviews', $reviews);
    }
}
