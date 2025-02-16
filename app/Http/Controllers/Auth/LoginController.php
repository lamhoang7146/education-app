<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Predis\Client as Redis;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function show()
    {
        return Inertia::render('Auth/Login', [
            'verified' => session('verified'),
            'message' => session('message'),
            'status' => session('status')
        ]);
    }

    public function login(Redis $redis)
    {

//------------------- Validations -------------------//
        $credentials = request()->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

//------------------- After checking input data, I will check redis if email is not exist -------------------//
        if (empty($redis->exists('email:' . crc32($credentials['email'])))) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

//------------------- Check email has been verified -------------------//
        if (!User::find($redis->get('email:' . crc32($credentials['email'])))->hasVerifiedEmail()) {
            return back()->with('verified', true);
        }


//------------------- After checking input data, I will log in with 1 condition is user's status must be active -------------------//
        if (Auth::attempt([...$credentials, 'status' => 1], request()->remember)) {
            request()->session()->regenerate();
            return redirect()->route('home')->with('success', 'You are now logged in');
        }


//------------------- Otherwise, notification to user that your account is not incorrect -------------------//
        return back()->withErrors([
            'email' => 'Your email or password is incorrect.',
        ]);

    }
}
