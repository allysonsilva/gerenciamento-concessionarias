<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginAction;
use App\Http\Controllers\Auth\LogoutAction;
use App\Http\Controllers\Auth\RegisterAction;
use App\Http\Controllers\Auth\EmailVerifyAction;
use App\Http\Controllers\Auth\RefreshTokenAction;

Route::middleware('guest')->name('guest.')->group(function () {
    Route::post('signup', RegisterAction::class)->name('signup');

    Route::post('login', LoginAction::class)->name('login');
});

Route::middleware('auth')->name('logged.')->group(function () {
    Route::get('/email/verify/{id}/{hash}', EmailVerifyAction::class)->name('email.verify');

    Route::delete('logout', LogoutAction::class)->name('logout');
    Route::put('refresh', RefreshTokenAction::class)->name('refresh');
});
