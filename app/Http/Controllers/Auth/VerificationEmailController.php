<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\VerificationEmail;
use Carbon\Carbon;
use Predis\Client as Redis;
use Inertia\Inertia;

class VerificationEmailController extends Controller
{
    public function show(){
        return Inertia::render('Auth/VerifyEmail',[
            'message'=>session('message'),
            'status'=>session('status')
        ]);
    }
    public function form(){
        return Inertia::render('Auth/VerifyEmailForm',[
            'message'=>session('message'),
            'status'=>session('status')
        ]);
    }
    public function resend(Redis $redis){
        $credentials = request()->validate([
            'email' => 'required|email'
        ]);
        if(empty($redis->get('email:'. crc32($credentials['email'])))){
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.'
            ]);
        }
        $user = User::find($redis->get('email:'. crc32($credentials['email'])));
        $user->notify(new VerificationEmail($user->id));
        return back()->with([
            'message'=>'Check your email for further instructions.',
            'status'=>true
        ]);
    }
    public function verify(){
        $user = User::find(request()->route('id'));
        if (!$user) {
            return redirect()->route('form-verify-email')->with([
                'message'=> 'User not found!',
                'status'=> false,
            ]);
        }

        if (Carbon::now()->timestamp > request()->route('expired')) {
            return redirect()->route('form-verify-email')->with([
                'message'=> 'Verification link has expired!',
                'status'=> false,
            ]);
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }
        return redirect()->route('login')->with([
            'message'=> 'Your email has been verified, now you can login!',
            'status'=> true
        ]);
    }
}
