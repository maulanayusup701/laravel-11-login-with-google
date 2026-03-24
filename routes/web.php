<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'authenticate')->name('login.post');
    Route::get('/auth/google', 'redirectToGoogle')->name('auth.google');
    Route::get('/auth/google/callback', 'handleGoogleCallback')->name('auth.google.callback');
});

Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->name('verification.verify');

Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        return view('welcome');
    })->name('home');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Routes ini butuh auth karena menampilkan form dan resend
    Route::get('/email/verify', [VerificationController::class, 'showVerificationForm'])
        ->name('verification.notice');
    Route::post('/email/resend', [VerificationController::class, 'resendVerification'])
        ->name('verification.resend');
});
