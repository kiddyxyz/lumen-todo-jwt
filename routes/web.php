<?php

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/auth', 'AuthenticationController@store');
$router->put('/auth/create', 'AuthenticationController@create');

$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->group(['prefix' => 'todo'], function () use ($router) {
        $router->get('/', 'TodoController@index');
        $router->put('/', 'TodoController@store');
        $router->post('/finish/{id}', 'TodoController@finish');
        $router->post('/{id}', 'TodoController@update');
        $router->delete('/{id}', 'TodoController@delete');
    });
});
