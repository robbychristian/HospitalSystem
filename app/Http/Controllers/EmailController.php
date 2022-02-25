<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Kreait\Firebase\Contract\Firestore;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Mail\EmailVerification;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{

    public function __construct(Firestore $firestore){ 
        $this->firestore = $firestore; 
        $this->middleware('auth');
        $this->middleware('notVerified');
    }

    public function index(){
        $documents = $this->firestore->database()->collection('Verify')->documents()->rows();

        $code = null;
        foreach($documents as $document){
            $data = $document->data();

            if($data['uid'] == Auth::user()->id_fb){
                $code = [
                    'id' => $document->id(),
                    'code' => $data['code'],
                    'expiration' => $data['expiration']->formatAsString(),
                ];

            }
            
        }
        return view('pages.email')->with('verify', json_encode($code));
    }

    public function insert(){
        $document = $this->firestore->database()->collection('Verify')->newDocument();

        $code = Str::upper(Str::random(6));
        $expiration = now()->addMinutes(20);

        $document->set([
            'uid' => Auth::user()->id_fb,
            'code' => $code,
            'expiration' => $expiration,
        ]);

        $data = [
            'id' => $document->id(),
            'code' => $code,
            'expiration' => $expiration,
        ];

        return $data;
    }

    public function update($id){
        $code = Str::upper(Str::random(6));
        $expiration = now()->addMinutes(20);

        $this->firestore->database()->collection('Verify')->document($id)->update([
            ['path' => 'code', 'value' => $code],
            ['path' => 'expiration', 'value' => $expiration],
        ]);

        $verify = [
            'id' => $id,
            'code' => $code,
            'expiration' => $expiration,
        ];

        return $verify;
    }
    
    public function sendVerificationCode($verify, $email){
        
        try{
            Mail::to($email)->queue(new EmailVerification($verify));
        }
        catch(\Exception $e){
            dd($e);
            return true;
        }
        return false;
    }

    public function resend(Request $request){
        
        $verify = $this->update($request->id);

        $hasError = $this->sendVerificationCode($verify, Auth::user()->email);

        if($hasError){
            return [
                'error' => 'yes',
                'message' => 'Error the code has not been sent Reasons: no internet connection or error in the database please refresh the page',
            ];
        }

        return [
            'message' => 'The code has been successfully sent into your email!',
            'verify' => json_encode($verify),
        ];
    }

    public function request(){
        
        $verify = $this->insert();

        $hasError = $this->sendVerificationCode($verify, Auth::user()->email);

        if($hasError){
            return [
                'error' => 'yes',
                'message' => 'Error the code has not been sent Reasons: no internet connection or error in the database please refresh the page',
            ];
        }

        return [
            'message' => 'The code has been successfully sent into your email!',
            'verify' => json_encode($verify),
        ];

    }

    public function verify(Request $request){
        $id = $request->id;

        $this->firestore->database()->collection('Verify')->document($id)->delete();

        $user = Auth::user();
        $user->isVerified = true;
        $user->save();

        return [ 'success' => 'yes' ] ;
    }
}
