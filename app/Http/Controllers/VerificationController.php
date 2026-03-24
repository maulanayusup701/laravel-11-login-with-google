<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /**
     * Show email verification notice
     */
    public function showVerificationForm(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        return view('auth.verify-email');
    }

    /**
     * Verify email
     */
    public function verify(Request $request, $id, $hash)
    {
        $user = User::find($id);

        // User not found
        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Link verifikasi tidak valid.');
        }

        // Invalid hash
        if (!hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            return redirect()->route('login')
                ->with('error', 'Link verifikasi tidak valid.');
        }

        // Already verified
        if ($user->hasVerifiedEmail()) {
            Auth::login($user);
            return redirect()->route('home')
                ->with('success', 'Email Anda sudah diverifikasi.');
        }

        // Mark as verified and login
        $user->markEmailAsVerified();
        Auth::login($user);

        return redirect()->route('home')
            ->with('success', 'Email Anda berhasil diverifikasi. Selamat datang!');
    }

    /**
     * Resend verification email
     */
    public function resendVerification(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home')
                ->with('info', 'Email Anda sudah diverifikasi.');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('success', 'Link verifikasi telah dikirim ke email Anda.');
    }
}
