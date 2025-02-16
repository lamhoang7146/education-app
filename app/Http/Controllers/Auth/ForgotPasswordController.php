<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Predis\Client as Redis;
use Inertia\Inertia;

class ForgotPasswordController extends Controller
{
    public function show(){
        return Inertia::render('Auth/ForgotPassword',[
            'message'=>session('message'),
            'status'=>session('status')
        ]);
    }
    public function forgotPassword(Redis $redis){
        request()->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            request()->only('email')
        );

        return $status === Password::ResetLinkSent
            ? back()->with(['message' => __($status),'status'=>true])
            : back()->withErrors(['email' => __($status)]);
    }

}
