<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\UnauthorizedException;

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

    //all posts with related user
    public function list(){
        return Post::with('user')->withCount('comments')->get();
    }

    //single post given an id with comments and user related
    public function singlePost(Request $request, $postId){
        $commentEnable=$request->input('comments');
        
        return Post::with('user')->when($commentEnable,function($query,$commentEnable){
            $query->with('comments');
        })->findOrFail($postId);
    }

    //create a new post from the request
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

    //edit a post if the user is related with the post
    public function edit(Request $request, $postId){
        // $userId = Auth::user()->id;
        $post=Post::findOrFail($postId);
        if (Gate::allows('upOrDel-post',$post)){
            // return 'user id is the same as post user_id';
            $this->validate($request,[
                'title'=>'required_without:content',
                'content'=>'required_without:title'
            ]);
            $post->fill($request->all());
            $post->save();
            return $post;
        }
        throw new UnauthorizedException('you don\'t own the post you are trying to edit');
    }

    //delete a post if the user is related with the post
    public function delete($postId){
        // $userId=Auth::user()->id;
        $post=Post::findOrFail($postId);
        if(Gate::allows('upOrDel-post',$post)){
            $post->delete();
            return [];
        }
        // if($userId==$post->user_id){
        // }
        throw new UnauthorizedException('you don\'t own the post you are trying to delete');
    }
    //
}
