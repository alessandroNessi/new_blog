<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

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

    public function create(Request $request){
        $userId = Auth::user()->id;
        $post=Post::findOrFail($request->input('post_id'));
        $this->validate($request,[
            'content' => 'required|max:1000',
        ]);
        $comment=new Comment;
        $comment->fill($request->all());
        $comment->user_id=$userId;
        $comment->post_id=$post->id;
        $comment->save();
        return $comment;
    }
    
    public function edit(Request $request, $commentId){
        $userId = Auth::user()->id;
        $comment=Comment::findOrFail($commentId);
        if($comment->user_id==$userId){
            $comment->content=$request->content;
            $comment->save();
            return $comment;
        }
        throw new UnauthorizedException('you don\'t own the comment you are trying to edit');
    }

    public function delete($commentId){
        $userId = Auth::user()->id;
        $comment=Comment::findOrFail($commentId);
        if($comment->user_id==$userId){
            $comment->delete();
            return [];
        }
        throw new UnauthorizedException('you don\'t own the comment you are trying to delete');
    }

}
