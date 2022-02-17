<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
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
    public function list(){
        return Post::with('user')->withCount('comments')->get();
    }
    public function singlePost(Request $request, $postId){
        $commentEnable=$request->input('comments');
        
        return Post::with('user')->when($commentEnable,function($query,$commentEnable){
            $query->with('comments');
        })->findOrFail($postId);
    }

    public function create(Request $request){
        $userId = Auth::user()->id;
        $this->validate($request,[
            'title'=>'required|max:1000',
            'content'=>'required|max:1000'
        ]);
        $post=new Post;
        $post->fill($request->all());
        $post->user_id=$userId;
        $post->save();
        return $post;
    }

    public function edit(Request $request, $postId){
        $userId = Auth::user()->id;
        $post=Post::findOrFail($postId);
        if($post->user_id==$userId){
            if($request->title){
                $post->title=$request->title;
            }
            if($request->content){
                $post->content=$request->content;
            }
            $post->save();
            return $post;
        }
        abort(401);
    }

    public function delete($postId){
        $userId=Auth::user()->id;
        $post=Post::findOrFail($postId);
        if($userId==$post->user_id){
            $post->delete();
            return [];
        }
        abort(401);
    }
    //
}
