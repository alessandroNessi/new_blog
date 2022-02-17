<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    // public function list(){
        // return Post::with('user')->withCount('comments')->get();
    // }
    public function getComment($commentId){
        return Comment::with('user')->findOrFail($commentId);
    }
    public function getPostComments(Request $request){
        $postId=$request->input('postId');
        return Comment::where('post_id',$postId)->with('user')->get();
    }
    //
}
