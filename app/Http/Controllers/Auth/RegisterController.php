<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\VerificationEmail;
use Predis\Client as Redis;
use Inertia\Inertia;

class RegisterController extends Controller
{
    public function show()
    {
        return Inertia::render('Auth/Register');
    }

    public function register(Redis $redis)
    {
//------------------- Validations -------------------//
        $credentials = request()->validate([
            'name' => 'required|string|max:255|min:3',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

//------------------- After checking input data, I will check redis if email has been exist -------------------//
        if (!empty($redis->exists('email:' . crc32($credentials['email'])))) {

//------------------- If has email of user in redis, I will send info to user that email existed -------------------//
            return back()->withErrors(['email' => 'Email already exists.'])->onlyInput('email');
        }


//------------------- I will add data of to the mysql and default role_id == 2 -------------------//
//------------------- It means that is user role -------------------//
        $user = User::create([...$credentials, 'role_id' => 2]);

        //------------------- I will add email of user to the redis -------------------//
        $redis->set('email:' . crc32($credentials['email']), $user->id);

//------------------- I will save data in redis to avoid append only file -------------------//
        $redis->save();

//------------------- I will send their email to verified email and avoid spam account -------------------//
        $user->notify(new VerificationEmail($user->id));

//------------------- Finally, redirect to the login page and show message to user that -------------------//
//------------------- "Your account have been registered, please check the email and active the account to use my application" -------------------//
        return redirect()->route('verify-email')->with([
            'message'=> 'We just sent an email with a link to verify your account.',
            'status'=> true
        ]);
    }
}
