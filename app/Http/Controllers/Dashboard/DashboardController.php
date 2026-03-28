<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function home()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        return view('welcome');
    }
}
