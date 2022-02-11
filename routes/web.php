<?php

use Illuminate\Support\Facades\Route;

// Imports
use App\Http\Controllers\TestController;
use App\Http\Controllers\LoginController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('test')->name('test.')->group(function () {
    Route::get('/', [TestController::class, 'index']);
});

Route::get('/', [LoginController::class, 'LoginScreen']);
Route::post('/login', [LoginCotnroller::class, 'Login']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
