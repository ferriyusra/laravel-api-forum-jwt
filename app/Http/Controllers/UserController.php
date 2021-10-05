<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function show($username)
    {
        $user = User::where('username', $username)
        ->select('username', 'created_at')
        ->first();

        if(!$user){
            return response()->json([
                'message' => 'Failed get user',
            ]);
        }

        return $user;

        // return User::where('username', $username)
        // ->select('username', 'created_at')
        // ->first();
    }
}
