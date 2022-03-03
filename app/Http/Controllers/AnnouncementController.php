<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Kreait\Firebase\Contract\Firestore;
use Kreait\Firebase\Contract\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;

class AnnouncementController extends Controller
{
    public function __construct(Firestore $firestore, Storage $storage){ 
        $this->firestore = $firestore; 
        $this->storage = $storage;
        $this->middleware('auth');
        $this->middleware('verified');
    }

    public function index(){

        $page="Announcement";
        $active="announcement";

        $documents = $this->firestore->database()->collection("Announcements");

        if( Auth::user()->isAdmin){
            $documents = $documents->documents()->rows();
        }
        else{
            $documents = $documents->where('author_id', '==', Auth::user()->id_fb)->documents()->rows();
        }

        $announcements = [];
        foreach ($documents as $document) {
            $data = $document->data();
            $data['id'] = $document->id();
            $data['date'] = Carbon::parse($data['date'])->format('F d, Y');

            array_push( $announcements, $data );
        }

        $announcements = json_encode($announcements);

        
        return view('pages.announcement')->with('page', $page)->with('active', $active)
                                    ->with('announcements', $announcements);
    }
    
    public function create($data, $uid){
        $announce = $this->firestore->database()->collection('Announcements')->newDocument();

        $announce->set([
            'id'=> $announce->id(),
            'date' => now()->format('D, m/d/y'),
            'title' => $data['title'],
            'photoUrl' => $data['photo'],
            'category' => $data['category'],
            'body' => $data['body'],
            'drId' => $uid->id_fb,
            'like' => null,
            'share' => null,
            'reference' => "Optional Link",
            'state' => 'approved', // pending
            'timeStamp' => (string) now()->timestamp,
            'author' => $uid->name,
            'authorPhoto' => $uid->photoUrl,
        ]);

        $data = $announce->snapshot()->data();
        $data['id'] = $announce->id();

        return $data;
    }
    
    public function addFile($file){

        $firebase_storage_path = 'Announcement Photo/';  
        $localfolder = public_path('firebase-temp-uploads') .'/';  
        $sample = '';

        $fileName = $file->hashName(); 

        if ($file->move($localfolder, $fileName)) {  

            $uploadedfile = fopen($localfolder.$fileName, 'r');  
            $sample = $this->storage->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $fileName]);
              
            unlink($localfolder . $fileName); 
        }

        return $sample->identity()['object'];
    }

    public function validation( array $data){

        return Validator::make($data, [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:255', ],
            'category' => ['required', 'string', 'max:255', ],
            'photo' => ['nullable ', 'image', 'mimes:jpg,png,jpeg', ],
        ]);
    }

    public function add(Request $request){
        $data = $request->all();

        $validator = $this->validation($data);
        
        if($validator->fails()){
            return $validator->errors()->add('hasError', true);
        }

        if($data['photo'] != null){
            $data['photo'] = $this->addFile($request->photo);
        }
        
        $announce = $this->create($data, $request->user());

        if($announce == null){
            return ['error' => 'error in database please make sure you have internet or refresh the page!'];
        }

        return [
            'success' => 'Success! Doctor has been added!',
            'announce' => json_encode($announce),
        ];
    }

    public function deleteObject($objectName){ $this->storage->getBucket()->object($objectName)->delete(); }

    public function updateAnnounce($data){
        $updateArray = [
            ['path' => 'title', 'value' => $data['title']],
            ['path' => 'category', 'value' => $data['category']],
            ['path' => 'body', 'value' => $data['body']],
            ['path' => 'photoUrl', 'value' => $data['photo'] ],
        ];
        
        $announce = $this->firestore->database()->collection('Announcements')->document($data['id']);
        $announce->update($updateArray);

        $data = $announce->snapshot()->data();
        $data['id'] = $announce->id();

        return $data;

    }

    public function update(Request $request){
        $data = $request->all();

        $validator = $this->validation($data);
        if($validator->fails()){
            return $validator->errors()->add('hasError', true);
        }

        if($data['photo'] != null || $data['photo'] != ''){
            $data['photo'] = $this->addFile($request->photo);
            $this->deleteObject($data['oldPhoto']);
        }
        else{
            $data['photo'] = $data['oldPhoto'];
        }

        $announce = $this->updateAnnounce($data);

        return [
            'success' => 'Success! Annoucement has been updated!',
            'announce' => json_encode($announce),
        ];
    }

    public function delete(Request $request){
        $data = $request->all();

        if($data['photoUrl'] != '' || $data['photoUrl'] != null)
            $this->deleteObject($data['photoUrl']);

        $this->firestore->database()->collection('Announcements')->document($data['id'])->delete();

        return ['success' => 'Announcement has been Successfuly Deleted!'];
    }
}
