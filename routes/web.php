<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::controller(UserController::class)->group(function (){
    Route::get('dashboard','index')->name('dashboard')->middleware(['auth', 'verified']);

    Route::get('chat/{id}','userchat')->name('chat');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
