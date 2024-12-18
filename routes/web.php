<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\GoogleCalendarController;

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

// Przekierowanie strony głównej na listę zadań
Route::get('/', function () {
    return redirect()->route('tasks.index');
});

// Trasy uwierzytelniania Laravel Breeze
require __DIR__.'/auth.php';

// Grupa tras chronionych przez middleware 'auth'
Route::middleware(['auth'])->group(function () {
    Route::resource('tasks', TaskController::class);
    Route::get('tasks/share/{task}', [TaskController::class, 'share'])->name('tasks.share');
    Route::get('tasks/history/{task}', [TaskController::class, 'history'])->name('tasks.history');
    Route::post('tasks/add-to-google-calendar/{task}', [TaskController::class, 'addToGoogleCalendar'])->name('tasks.addToGoogleCalendar');

    // Trasy dla Google Calendar
    Route::get('google-calendar/connect', [GoogleCalendarController::class, 'redirectToGoogle'])->name('google-calendar.connect');
    Route::get('google-calendar/callback', [GoogleCalendarController::class, 'handleGoogleCallback'])->name('google-calendar.callback');

});

// Trasy publiczne
Route::get('tasks/public/{token}', [TaskController::class, 'publicShow'])->name('tasks.public.show');
