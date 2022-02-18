<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        //FOR PREMIUM USERS
        // Gate::before(function ($user, $ability) {
        //     if ($user->isAdministrator()) {
        //         return true;
        //     }
        // });

        Gate::define('upOrDel-post', function (User $user, Post $post) {
            return $user->id === $post->user_id;
        });

        Gate::define('upOrDel-comment',function (User $user, Comment $comment){
            return $user->id===$comment->user_id;
        });
        
        Gate::define('checkPremium-user',function (User $user){
            return $user->subscription=='premium';
        });

        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->header('Authorization')) {
                // se nell'header esiste un parametro Authorization
                return User::where('api_token', $request->header('Authorization'))->first();
                //ritorno user, se non lo trovo null
            }
        });
    }
}
