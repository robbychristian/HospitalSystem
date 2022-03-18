<?php

use Illuminate\Support\Facades\Route;

// Imports
use App\Http\Controllers\TestController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ProfileController;

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

Route::prefix('announcement')->name('announcement.')->group(function () {
    Route::get('/', [AnnouncementController::class, 'index']);
    Route::get('/add', [AnnouncementController::class, 'add']);
    Route::post('/delete', [AnnouncementController::class, 'delete']);
});

Route::prefix('test')->name('test.')->group(function () {
    Route::get('/', [TestController::class, 'index']);
    Route::get('/calendar', [CalendarController::class, 'index']);
    Route::post('/addappointment', [CalendarController::class, 'store']);
    // Route::get('/insert', [TestController::class, 'insert']);
});

Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
});

Route::prefix('email')->name('email.')->group(function () {
    Route::get('/', [EmailController::class, 'index'])->name('index');
    Route::post('/request', [EmailController::class, 'request']);
    Route::post('/resend', [EmailController::class, 'resend']);
    Route::post('/verify', [EmailController::class, 'verify']);
});


Route::prefix('doctor')->name('doctor.')->group(function () {
    Route::post('/add', [DoctorController::class, 'add']);
    Route::post('/delete', [DoctorController::class, 'delete']);
    Route::post('/update', [DoctorController::class, 'update']);
});


Route::get('/', [LoginController::class, 'LoginScreen']);

Route::post('/sign-in', [LoginController::class, 'Login']);

Route::post('/offline', [LoginController::class, 'offline']);

Route::prefix('appointment')->name('appointment.')->group(function () {
    Route::get('/', [AppointmentController::class, 'index']);
    Route::post('/add', [AppointmentController::class, 'add']);
    Route::post('/delete', [AppointmentController::class, 'delete']);
    Route::post('/update', [AppointmentController::class, 'update']);
    Route::post('/status', [AppointmentController::class, 'status']);
    Route::post('/lab', [AppointmentController::class, 'lab']);
});

Route::prefix('calendar')->name('calendar.')->group(function () {
    Route::get('/', [CalendarController::class, 'index']);
    Route::post('/addappointment', [CalendarController::class, 'store']);
});

Route::prefix('inquiry')->name('inquiry.')->group(function () {
    Route::get('/', [InquiryController::class, 'index']);
    Route::post('/image', [InquiryController::class, 'signedUrl']);
    Route::post('/prescribe', [InquiryController::class, 'prescribe']);
});

Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'index']);
    Route::post('/personal', [ProfileController::class, 'personal']);
    Route::post('/clinic', [ProfileController::class, 'clinic']);
    Route::post('/password', [ProfileController::class, 'password']);
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
