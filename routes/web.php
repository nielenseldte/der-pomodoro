<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\pomodoroSettingsUpdate;
use App\Http\Livewire\SettingsComponent;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); // we call the index method of the HomeController
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function(){
    Route::get('/settings', [pomodoroSettingsUpdate::class, 'index'])->name('settings');
    Route::post('/settings', [SettingsComponent::class, 'save'])->name('update.settings');
});




Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/timer', function () {
        return view('timer');
    })->name('timer');
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/stats', [StatsController::class, 'index'])->name('stats');
    });



Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/about', function () {
        return view('about');
    })->name('about');
});




