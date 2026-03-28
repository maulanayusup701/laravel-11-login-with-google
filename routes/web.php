<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\DashboardController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'authenticate')->name('login.post');

    Route::get('/auth/google', 'redirectToGoogle')->name('auth.google');

    Route::get('/auth/google/callback', 'handleGoogleCallback')->name('auth.google.callback');
    Route::post('/auth/google/callback.post', 'handleGoogleCallback')->name('auth.google.callback.post');
});

Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');

Route::middleware('auth')->group(function () {
    Route::get('/home', [DashboardController::class, 'home'])->name('home');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Routes ini butuh auth karena menampilkan form dan resend
    Route::controller(VerificationController::class)->group(function () {
        Route::get('/email/verify', 'showVerificationForm')->name('verification.notice');
        Route::post('/email/resend', 'resendVerification')->name('verification.resend');
    });
});
