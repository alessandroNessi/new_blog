<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

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
    //
}
