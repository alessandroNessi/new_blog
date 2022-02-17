<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['prefix' => 'posts'],function() use($router){
    $router->get('', 'PostController@list');
    $router->get('{postId}', 'PostController@singlePost');
});

$router->group(['prefix' => 'comments'],function() use($router){
    $router->get('','CommentController@getPostComments');
    $router->get('{commentId}','CommentController@getComment');
});