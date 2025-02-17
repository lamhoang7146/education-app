<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
//use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Predis\Client as Redis;

class UserController extends Controller
{
    private array $data = [];
    public function store(Redis $redis)
    {
        $credentials = request()->validate([
            'name' => 'required|string|max:255|min:3',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:3072',
        ]);
        if (!empty($redis->exists('email:' . crc32($credentials['email'])))) {
            return back()->withErrors(['email' => 'Email already exists.'])->onlyInput('email');
        }
        $credentials['role_id'] = request('selectedRole')['id'];
        $credentials['status'] = request('selectedStatus')['id'];
        if (request()->hasFile('image')) {
            $credentials['image'] = Storage::disk('public')->put('profile', request()->file('image'));
        }
        $user = User::create($credentials);
        $redis->set('email:' . crc32($credentials['email']), $user->id);
        $redis->save();
        return redirect()->route('user.list')->with(['message' => 'User created successfully.', 'status' => true]);
    }

    public function index()
    {

        $this->data['roles'] = Role::select('id', 'name')->get();
        $this->data['users'] = User::with('getRole')
            ->where('id','!=',request()->user()->id)
            ->filter(request(['username', 'selectedRole', 'selectedStatus']))
            ->latest()
            ->paginate(10)
            ->withQueryString();
        $this->data['search'] = request('username');
        return Inertia::render('User/List', [
            ...$this->data,
            'message' => session('message'),
            'status' => session('status')
        ]);
    }

    public function status(User $user)
    {
        $user->status = !$user->status;
        $user->save();
        return back();
    }

    public function update(User $user)
    {
        $credentials = request()->validate([
            'name' => 'required|string|max:255|min:3',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:3072',
        ]);
        if(request()->hasFile('image')){
            $credentials['image'] = Storage::disk('public')->put('profile', request()->file('image'));
            if(!empty($user->image)){
                Storage::disk('public')->delete($user->image);
            }
        }

        $user->update([
            'name' => request('name'),
            'role_id' => request('selectedRole')['id'],
            'status' => request('selectedStatus')['id'],
            'image' => $credentials['image'],
        ]);

        return redirect()->route('user.list')->with(['message' => "Updated $user->name's information successfully!", 'status' => true]);

    }

}
