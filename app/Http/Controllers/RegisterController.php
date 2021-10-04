<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register()
    {
        $this->validate(request(), [
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        $user = User::create([
            'username' => request('username'),
                'email' => request('email'),
                'password' => Hash::make(request('password'))
        ]);

        // apakah generate token, auto login atau hanya response berhasil
        return response()->json([
            'message' => 'Successfuly register',
        ]);
    }
}
