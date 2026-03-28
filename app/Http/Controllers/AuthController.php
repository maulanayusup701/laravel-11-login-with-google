<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;
use Google_Client;

class AuthController extends Controller
{
    /**
     * Show login page
     */
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    /**
     * Handle login attempt
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Check if email is verified
            /** @var User $user */
            $user = Auth::user();
            if (!$user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            return redirect()->intended('home');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            return $request->has('credential')
                ? $this->handleOneTapCallback($request)
                : $this->handleLaravelSosialite();
        } catch (\Exception $e) {
            if ($request->has('credential')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Login gagal. Silakan coba lagi.'
                ], 500);
            }
            Log::error('Google OAuth Error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Login with Google failed. Please try again.');
        }
    }

    /**
     * Handle laravel sosialite
     */
    protected function handleLaravelSosialite()
    {
        $googleUser = Socialite::driver('google')->user();

        if (!$googleUser->getEmail()) {
            throw new \Exception('Email tidak tersedia dari akun Google.');
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            // Create new user
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'provider' => 'google',
                'provider_id' => $googleUser->getId(),
                'email_verified_at' => null,
                'password' => Hash::make(Str::random(24)),
            ]);

            // Send verification email
            $user->sendEmailVerificationNotification();

            Auth::login($user);

            return redirect()->route('verification.notice')
                ->with('success', 'Silakan verifikasi email Anda terlebih dahulu.');
        }

        // Update provider info if needed
        if ($user->provider !== 'google' || $user->provider_id !== $googleUser->getId()) {
            $user->update([
                'provider' => 'google',
                'provider_id' => $googleUser->getId(),
            ]);
        }

        Auth::login($user);

        // Cek apakah email sudah diverifikasi
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        return redirect()->intended('home');
    }

    /**
     * Handle One Tap callback
     */
    protected function handleOneTapCallback(Request $request)
    {
        $credential = $request->input('credential');

        // Verify the credential
        $client = new Google_Client([
            'client_id' => config('services.google.client_id'),
        ]);

        $payload = $client->verifyIdToken($credential);

        if (!$payload) {
            return response()->json([
                'success' => false,
                'message' => 'Credential tidak valid'
            ], 400);
        }

        // Cari atau buat user
        $user = User::firstOrCreate(
            ['email' => $payload['email']],
            [
                'name' => $payload['name'],
                'provider' => 'google',
                'provider_id' => $payload['sub'],
                'email_verified_at' => null,
                'password' => Hash::make(Str::random(24)),
            ]
        );

        // Jika user baru dibuat, kirim email verifikasi
        if ($user->wasRecentlyCreated) {
            $user->sendEmailVerificationNotification();
        }

        Auth::login($user);

        // Cek apakah email sudah diverifikasi
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'redirect' => route('home')
        ]);
    }
}
