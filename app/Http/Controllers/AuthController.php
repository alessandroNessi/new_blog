<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthController extends Controller
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

    public function login(Request $request){
        $this->validate($request,[
            'email' => 'required|email',
            'password' => 'required',
        ]);
        //se non supera la validazione ritorna 422
        $user=User::where(['email'=>$request->input('email')])->firstOrFail();
        if(Hash::check($request->input('password'),$user->password)){
            return ['api_token'=>$user->api_token];
        }
        throw new UnauthorizedHttpException('','password errata');
    }

    public function register(Request $request){
        $this->validate($request,[
            'email'=>'required|email|unique:users',
            // unique cerca nella tabella :tabella
            'password'=>'required|max:1000',
            'first_name'=>'required|max:1000',
            'last_name'=>'required|max:1000',
            'picture'=>'required|max:1000',
            // 'error'=>'required'
        ]);
        $user=new User;
        $user->fill($request->all());
        $user->password=Hash::make($request->input('password'));
        $user->api_token=Str::random(64);
        $user->save();
        return $user;
    }

    //
}
