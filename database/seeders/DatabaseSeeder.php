<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $firestore = app('firebase.firestore');
        $database = $firestore->database();
        $datas = $database->collection('Doctors')->documents()->rows(); //array
        $users = [];
        
        foreach ($datas as $data) {
            $user = $data->data();
            $user['id'] = $data->id();
            array_push($users, $user);
        }

        foreach ($users as $user) {
            $this->create($user);
        }
    }

    
    public function create($user){
        User::create([
            'name' => $user['fName'] . ' ' . $user['lName'],
            'id_fb' => $user['id'],
            'fname' => $user['fName'],
            'lname' => $user['lName'],
            'phone' => $user['phone'],
            'email' => $user['email'],
            'about' => $user['about'],
            'clinicAddress' => $user['clinicAddress'],
            'joinDate' => $user['joinDate'],
            'isVerified' => $user['isVerified'],
            'gender' => $user['gender']  == 'true' ? true : false,
            'specialization' => $user['specialization'],
            'degree' => $user['degree'],
            'consultFee' => $user['consultFee'] == '' ? 0 : $user['consultFee'],
            'teleconsultFee' => $user['teleconsultFee'] == '' ? 0 : $user['teleconsultFee'],
            'isAdmin' => $user['isAdmin'],
            'photoUrl' => $user['photoUrl'],
            'totalPrescribe' => $user['totalPrescribe'] == '' ? 0 : $user['totalPrescribe'],
            'totalEarnings' => $user['totalEarnings'] == '' ? 0 : $user['totalEarnings'],
            'provideTeleService' => $user['provideTeleService'],
            'password' => Hash::make($user['password']),
        ]);
    }
}
