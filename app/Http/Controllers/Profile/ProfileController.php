<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class ProfileController extends Controller
{
    private array $data = [];
    public function index(){
        $this->data['user'] = auth()->user();
        return Inertia::render('Profile/Index', [
            ...$this->data,
            'message' => session('message'),
            'status' => session('status'),
        ]);
    }
    public function updateProfile()
    {
        $userId = auth()->user()->id;
        $credentials = request()->validate([
            'name' => ['required', 'string','min:3', 'max:255'],
        ]);
        User::where('id', $userId)->update([
            'name'=> $credentials['name'],
        ]);

        return redirect()->route('profile.index')->with([
            'message' => 'Profile updated successfully',
            'status'=>true
        ]);
    }
    public function updatePassword(){
        $fields = request()->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:4'],
        ]);
        request()->user()->password = Hash::make($fields['password']);
        request()->user()->save();
        return redirect()->route('profile.index')->with([
            'message' => 'Password updated successfully',
            'status'=>true
        ]);
    }
}
