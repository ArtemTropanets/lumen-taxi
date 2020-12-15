<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use \Illuminate\Support\Facades\Route;

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

$router->get('/{any:.*}', function () {
    return view('app');
});

$router->group(['prefix' => 'person'], function () use ($router) {
    $router->post('getAll',  [
        'as' => 'getPersons',
        'uses' => 'PersonController@getAll'
    ]);
});



