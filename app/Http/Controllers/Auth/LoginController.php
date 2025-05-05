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


        $credentials = request()->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);


        if (empty($redis->exists('email:' . crc32($credentials['email'])))) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }


        if (!User::find($redis->get('email:' . crc32($credentials['email'])))->hasVerifiedEmail()) {
            return back()->with('verified', true);
        }

        if (Auth::attempt([...$credentials, 'status' => 1], request()->remember)) {
            request()->session()->regenerate();
            return redirect()->intended()->with('success', 'You are now logged in');
        }

        return back()->withErrors([
            'email' => 'Your email or password is incorrect.',
        ]);

    }
    public function redirectToLogin()
    {
        // Lưu URL hiện tại vào session
        session(['url.intended' => url()->previous()]);

        // Chuyển hướng đến trang đăng nhập
        return redirect()->route('login');
    }
}
