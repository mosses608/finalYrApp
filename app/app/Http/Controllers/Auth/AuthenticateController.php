<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticateController extends Controller
{
    //
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('login')->with(['success' => 'Logged out successfully!']);
    }

    public function auth(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string|min:5',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User does not exist!');
        }

        if ($user->blocked_at && Carbon::now()->lt(Carbon::parse($user->blocked_at)->addMinutes(30))) {
            $blockedAt = Carbon::parse($user->blocked_at);
            $timer = $blockedAt->diffInMinutes(Carbon::now());
            $finalTimer = number_format(30 - $timer);
            return back()->with('error', 'Your account is temporarily blocked for' . ' ' . $finalTimer . ' ' . ' minutes from now due to too many login attempts!');
        }

        if(Hash::check($request->password, $user->password)){
            $user->update([
                'login_attempts' => 0,
                'blocked_at' => null,
            ]);

            Auth::login($user);

            return redirect()->route('dashboard')->with('success','Logged in successfully!');
        }

        $user->increment('login_attempts');
        $remainingAttempts = 3 - $user->login_attempts;

         if ($user->login_attempts >= 3) {
            $user->update(['blocked_at' => Carbon::now()]);
            return back()->with('error', 'Too many failed login attempts. Your account is now blocked for 30 minutes.');
        }

        return back()->with('error', 'Incorrect login credentials! ' . $remainingAttempts . ' attempt(s) left.');
    }
}
