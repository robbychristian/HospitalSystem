<?php

use Illuminate\Support\Facades\Route;

// Imports
use App\Http\Controllers\TestController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\CalendarController;

use App\Http\Controllers\InquiryController;
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
    Route::get('/calendar', [CalendarController::class, 'index']);
    Route::post('/addappointment', [CalendarController::class, 'store']);
    // Route::get('/insert', [TestController::class, 'insert']);
});

Route::get('/', [LoginController::class, 'LoginScreen']);

Route::post('/sign-in', [LoginController::class, 'Login']);

Route::post('/offline', [LoginController::class, 'offline']);

Route::prefix('patient')->name('patient.')->group(function () {
    Route::get('/', [PatientController::class, 'index']);
});
Route::prefix('calendar')->name('calendar.')->group(function () {
    Route::get('/', [CalendarController::class, 'index']);
});

Route::prefix('inquiry')->name('inquiry.')->group(function () {
    Route::get('/', [InquiryController::class, 'index']);
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
