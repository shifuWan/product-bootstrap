<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;

Route::controller(AuthenticationController::class)
    ->prefix('auth')
    ->group(function () {
        Route::get('/login', 'login')->name('login')->middleware('guest');
        Route::post('/login', 'loginPost')->name('login.post')->middleware('guest');
        Route::get('/logout', 'logout')->name('logout')->middleware('auth');
    });
