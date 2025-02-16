<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\TwoStepVerification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class TwoStepVerificationController extends Controller
{
    public function show(){
        return Inertia::render('Auth/TwoStepVerification',[
            'message'=>session('message'),
            'status'=>session('status')
        ]);
    }
    public function form(){
        return Inertia::render('Auth/FormTwoStepVerification',[
                'message'=>session('message'),
                'status'=>session('status')
            ]);
    }
    public function twoStepVerification(){
        $user = Auth::user();
        $code = rand(100000, 999999);
        $user->two_factor_secret = Hash::make($code);
        $user->two_factor_expires_at = Carbon::now()->addMinutes(3);
        $user->save();
        $user->notify(new TwoStepVerification($code));
        return redirect()->route('form-two-step-verification')->with([
            'message'=>'We have sent you two step verification code, please check your email.',
            'status'=>true
        ]);
    }
    public function handle(){
        $user = Auth::user();
        if(!Hash::check(request()->input('OTP'),$user->two_factor_secret)){
            return back()->with([
                'message'=>'Invalid OTP',
                'status'=>false
            ]);
        }
        if(Carbon::now() > $user->two_factor_expires_at){
            $user->resetTwoFactorSecret();
            return back()->with([
               'message'=>'Your two factor verification code has expired, please resend your two step verification code.',
               'status'=>false
            ]);
        }
        session()->put('two_factor',true);
        $user->resetTwoFactorSecret();
        return redirect()->intended('/');
    }
}
