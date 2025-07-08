<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name', 'asc')->get();
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'name' => 'required',
            'username' => 'required',
            'profile' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $validated_data['name'],
            'username' => $validated_data['username'],
            'profile' => $validated_data['profile'],
            'status' => true,
            'password' => $validated_data['password'],
            'password_laravel' => bcrypt($validated_data['password']),
        ]);

        request()->session()->flash('alertmessage', [
            'message' => 'Usuario agregado',
            'type' => 'success',
        ]);

        return back();
    }

    public function get_row()
    {
        $user = User::find(request('id'));
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        $validated_data = $request->validate([
            'name' => 'required',
            'username' => 'required',
            'profile' => 'required',
        ]);

        if(request('password') || request('password_confirmation')) {
            $this->validate(request(), [
                'password' => 'min:6|confirmed',
            ]);

            $user->password = request('password');
            $user->password_laravel = bcrypt(request('password'));
        }

        $user->name = $validated_data['name'];
        $user->username = $validated_data['username'];
        $user->profile = $validated_data['profile'];
        $user->save();

        request()->session()->flash('alertmessage', [
            'message' => 'Usuario actualizado',
            'type' => 'success',
        ]);

        return back();
    }

    public function detail(User $user)
    {
        return view('users.detail', compact('user'));
    }
}
