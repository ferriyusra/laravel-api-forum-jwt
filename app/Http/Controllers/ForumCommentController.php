<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AuthUserTrait;
use Illuminate\Support\Facades\Validator;
use App\Models\ForumComment;


class ForumCommentController extends Controller
{
    use AuthUserTrait;
 
    public function __construct()
    {
        return auth()->shouldUse('api');
    }

    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $forumId)
    {
        $this->validateRequest();
        $user = $this->getAuthUser();

        $user->forumComments()->create([
            'body' => request('body'),
            'forum_id' =>  $forumId
        ]);
        
        return response()->json(['message' => 'Successfully comment posted']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $forumId, $commentId)
    {
        $this->validateRequest();

        $forumComment = ForumComment::findOrFail($commentId);


        // check ownership
       $this->checkOwnership($forumComment->user_id);

        $forumComment->update([
                'body' => request('body'),
        ]);

        return response()->json([
            'message' => 'Successfuly comment updated',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($forumId, $commentId)
    { 
        $forumComment = ForumComment::findOrFail($commentId);

        // check ownership
        $this->checkOwnership($forumComment->user_id);

        $forumComment->delete();

        return response()->json([
            'message' => 'Successfuly comment deleted',
        ]);
    }

    private function validateRequest()
    {
        $validator = Validator::make(request()->all(), [
            'body' => 'required',
        ]);

        if($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit;
        }

    }
}
