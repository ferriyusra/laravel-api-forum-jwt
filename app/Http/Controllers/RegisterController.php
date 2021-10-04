<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register()
    {
        $validator = Validator::make(request()->all(), [
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(
                $validator->messages()
            );
        }

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
