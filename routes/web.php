<?php

use Illuminate\Support\Facades\Route;

// Imports
use App\Http\Controllers\TestController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\CalendarController;

use App\Http\Controllers\InquiryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\AnnouncementController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('announce')->name('announce.')->group(function(){
    Route::get('/', [AnnouncementController::class, 'index']);
    Route::post('/add', [AnnouncementController::class, 'add']);
    Route::post('/delete', [AnnouncementController::class, 'delete']);
    Route::post('/update', [AnnouncementController::class, 'update']);
});

Route::prefix('test')->name('test.')->group(function () {
    Route::get('/', [TestController::class, 'index']);
    Route::get('/calendar', [CalendarController::class, 'index']);
    Route::post('/addappointment', [CalendarController::class, 'store']);
    // Route::get('/insert', [TestController::class, 'insert']);
});

Route::prefix('dashboard')->name('dashboard.')->group(function(){
    Route::get('/', [DashboardController::class, 'index'])->name('index');
});

Route::prefix('email')->name('email.')->group(function(){
    Route::get('/', [EmailController::class, 'index'])->name('index');
    Route::post('/request', [EmailController::class, 'request']);
    Route::post('/resend', [EmailController::class, 'resend']);
    Route::post('/verify', [EmailController::class, 'verify']);
});


Route::prefix('doctor')->name('doctor.')->group(function(){
    Route::post('/add', [DoctorController::class, 'add']);
    Route::post('/delete', [DoctorController::class, 'delete']);
    Route::post('/update', [DoctorController::class, 'update']);
});


Route::get('/', [LoginController::class, 'LoginScreen']);

Route::post('/sign-in', [LoginController::class, 'Login']);

Route::post('/offline', [LoginController::class, 'offline']);

Route::prefix('patient')->name('patient.')->group(function () {
    Route::get('/', [PatientController::class, 'index']);
    Route::post('/add', [PatientController::class, 'add']);
    Route::post('/delete', [PatientController::class, 'delete']);
    Route::post('/update', [PatientController::class, 'update']);
});
Route::prefix('calendar')->name('calendar.')->group(function () {
    Route::get('/', [CalendarController::class, 'index']);
});

Route::prefix('inquiry')->name('inquiry.')->group(function () {
    Route::get('/', [InquiryController::class, 'index']);
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
