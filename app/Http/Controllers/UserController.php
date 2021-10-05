<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function show($username)
    {
        $user = new UserResource(User::where('username', $username)
                ->first());

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

    public function getActivity($username)
    {
        return new UserResource(User::where('username', $username)
                ->with('forums', 'forumComments')
                ->first());
    }
}
