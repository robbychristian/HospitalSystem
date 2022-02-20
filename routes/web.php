<?php

use Illuminate\Support\Facades\Route;

// Imports
use App\Http\Controllers\TestController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;

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

Route::prefix('test')->name('test.')->group(function () {
    Route::get('/', [TestController::class, 'index']);
    Route::get('/calendar', [TestController::class, 'calendar']);
    // Route::get('/insert', [TestController::class, 'insert']);
});

Route::prefix('dashboard')->name('dashboard.')->group(function(){
    Route::get('/', [DashboardController::class, 'index']);
});

Route::prefix('doctor')->name('doctor.')->group(function(){
    Route::post('/add', [DoctorController::class, 'add']);
    Route::post('/delete', [DoctorController::class, 'delete']);
});


Route::get('/', [LoginController::class, 'LoginScreen']);

Route::post('/sign-in', [LoginController::class, 'Login']);

Route::post('/offline', [LoginController::class, 'offline']);

Route::prefix('patient')->name('patient.')->group(function(){
    Route::get('/', [PatientController::class, 'index']);
});

Route::prefix('inquiry')->name('inquiry.')->group(function(){
    Route::get('/', [InquiryController::class, 'index']);
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
