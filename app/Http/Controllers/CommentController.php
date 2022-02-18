<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
    
    //get the comment given the id
    public function getComment($commentId){
        return Comment::with('user')->findOrFail($commentId);
    }

    //get all comments related to a post with user infos
    public function getPostComments(Request $request){
        $postId=$request->input('postId');
        return Comment::where('post_id',$postId)->with('user')->get();
    }

    //create a post associated with a registered user
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
    
    //edit the comment if the logged user is the one who created it
    public function edit(Request $request, $commentId){
        if(Gate::allows('checkPremium-user')){
            $comment=Comment::findOrFail($commentId);
            if(Gate::allows('upOrDel-comment',$comment)){
                $comment->content=$request->content;
                $comment->save();
                return $comment;
            }
            throw new UnauthorizedException('you don\'t own the comment you are trying to edit');
        }
        throw new UnauthorizedException('you aren\'t a premium user');
    }

    //delete a comment if the logged user is the one who created it
    public function delete($commentId){
        // return Auth::user();
        if(Gate::allows('checkPremium-user') ){
            $comment=Comment::findOrFail($commentId);
            if(Gate::allows('upOrDel-comment',$comment)){
                $comment->delete();
                return [];
            }
            throw new UnauthorizedException('you don\'t own the comment you are trying to delete');
        }
        throw new UnauthorizedException('you aren\'t a premium user');
    }

}
