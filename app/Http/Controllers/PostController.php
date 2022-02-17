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
    //$router->put('edit', 'Postcontroller@edit');
    public function edit(Request $request, $postId){
        $userId = Auth::user()->id;
        // $this->validate($request,[
        //     'postId'=>'required',
        // ]);
        $post=Post::findOrFail($postId);
        if($post->user_id==$userId){
            abort(404);
        }else{
            abort(405);
        }
    }
    //
}
