<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Kreait\Firebase\Contract\Firestore;
use App\Models\User;
use Carbon\Carbon;

use Kreait\Firebase\Contract\Storage;

class DashboardController extends Controller
{
    
    public function __construct(Firestore $firestore,  Storage $storage){ 
        $this->firestore = $firestore; 
        $this->storage = $storage;
        $this->middleware('auth');
    }
    
    public function index(){

        $page="Dashboard";
        $active="home";

        $doctors = User::where('isAdmin', false)->get();
        
        $documents = $this->firestore->database()->collection('Announcements')->documents()->rows();
        $announcements = [];
        foreach ($documents as $document) {
            $data = $document->data();
            $data['id'] = $document->id();
            $data['joindate'] = Carbon::parse($data['joindate'])->format('F d, Y');

            array_push( $doctors, $data );
        }

        $doctors = json_encode($doctors);
        $announcements = json_encode($announcements);

        
        return view('pages.dashboard')->with('page', $page)->with('active', $active)
                                    ->with('doctors', $doctors)->with('announcements', $announcements);
    }

}
