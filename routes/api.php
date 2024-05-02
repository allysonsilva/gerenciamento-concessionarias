<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MeController;
use App\Http\Controllers\ConcessionariaController;

Route::controller(MeController::class)
    ->prefix('me')
    ->name('me.')
    ->group(function () {
        Route::get('profile', 'showProfile')->name('profile.show');
        // Route::put('profile', 'profileUpdate')->name('profile.update');
    });

Route::middleware('auth-verified')->name('verified.')->group(function () {
    Route::apiResource('concessionarias', ConcessionariaController::class);
});
