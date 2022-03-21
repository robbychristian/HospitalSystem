<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InquiryController;

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

Route::post('/profile/update/{id_fb}/{pass}', [ProfileController::class, 'update']);

Route::get('/inquiry/download/{pdf}', [InquiryController::class, 'download']);

Route::prefix('calendar')->name('calendar.')->group(function () {
    Route::post('/delete', [CalendarController::class, 'destroy']);
});
