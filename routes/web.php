<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CategoryController;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::controller(AuthenticationController::class)
    ->prefix('auth')
    ->group(function () {
        Route::get('/login', 'login')->name('login')->middleware('guest');
        Route::post('/login', 'loginPost')->name('login.post')->middleware('guest');
        Route::get('/logout', 'logout')->name('logout')->middleware('auth');
    });

Route::controller(CategoryController::class)
    ->prefix('categories')
    ->group(function () {
        Route::get('/', 'index')->name('categories.index');
        Route::get('/create', 'create')->name('categories.create');
        Route::post('/', 'store')->name('categories.store');
        Route::get('/{category}/edit', 'edit')->name('categories.edit');
        Route::put('/{category}', 'update')->name('categories.update');
        Route::delete('/{category}', 'destroy')->name('categories.destroy');
    });