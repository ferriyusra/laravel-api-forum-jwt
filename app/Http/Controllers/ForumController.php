<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    public function __construct()
    {
        return auth()->shouldUse('api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        return Forum::with(['user:id,username'])->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->getValidationAttribute());

        if($validator->fails()){
            return response()->json(
                $validator->messages()
            );
        }

        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json([
                'message' => 'Not authenticated, you have to login first',
            ]);
        }

        $data = $user->forums()->create([
                'title' => request('title'),
                'body' => request('body'),
                'slug' => Str::slug(request('title'), '-') . '-' . time(),
                'category' => request('category'),
        ]);

        // apakah generate token, auto login atau hanya response berhasil
        return response()->json([
            'message' => 'Successfuly posted',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Forum::with(['user:id,username'])->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), $this->getValidationAttribute());

        if($validator->fails()){
            return response()->json(
                $validator->messages()
            );
        }

        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json([
                'message' => 'Not authenticated, you have to login first',
            ]);
        }

        $forum = Forum::findOrFail($id);
        // check ownership
        if($user->id != $forum->user_id){
            return response()->json([
                'message' => 'Not Authorized',
            ], 403);
        }

        $forum->update([
                'title' => request('title'),
                'body' => request('body'),
                'category' => request('category'),
        ]);

        return response()->json([
            'message' => 'Successfuly updated',
        ]);
    }

    private function getValidationAttribute()
    {
        return [
            'title' => 'required|min:5',
            'body' => 'required|min:10',
            'category' => 'required',
        ];

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { 
        $forum = Forum::findOrFail($id);

        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json([
                'message' => 'Not authenticated, you have to login first',
            ]);
        }

           // check ownership
           if($user->id != $forum->user_id){
            return response()->json([
                'message' => 'Not Authorized',
            ], 403);
        }

        $forum->delete();

        return response()->json([
            'message' => 'Successfuly deleted',
        ], 403);
    }
}
