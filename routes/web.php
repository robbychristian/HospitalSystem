<?php

use Illuminate\Support\Facades\Route;

// Imports
use App\Http\Controllers\TestController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;

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
    Route::get('/inquiry', [TestController::class, 'inquiry']);
    Route::get('/calendar', [TestController::class, 'calendar']);
    // Route::get('/insert', [TestController::class, 'insert']);
});

Route::get('/', [LoginController::class, 'LoginScreen']);
Route::get('/debugger', [LoginController::class, 'DebuggerPage']);

Route::post('/sign-in', [LoginController::class, 'Login']);

Route::prefix('patient')->name('patient.')->group(function () {
    Route::get('/', [PatientController::class, 'index']);
});
Route::prefix('calendar')->name('calendar.')->group(function () {
    Route::get('/', [PatientController::class, 'index']);
});



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
