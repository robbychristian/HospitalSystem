<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\CalendarController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('announcement')->name('announcement.')->group(function () {
    Route::post('/add', [AnnouncementController::class, 'add']);
    Route::post('/delete', [AnnouncementController::class, 'delete']);
    Route::post('/update', [AnnouncementController::class, 'update']);
});

<<<<<<< Updated upstream
Route::prefix('calendar')->name('calendar.')->group(function () {
    Route::post('/delete', [CalendarController::class, 'destroy']);
});
=======
Route::post('/add', [AnnouncementController::class, 'add']);

>>>>>>> Stashed changes
