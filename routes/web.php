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


$router->group(['prefix' => 'person'], function () use ($router) {
    $router->get('getAll',  [
        'middleware' => 'only_ajax',
        'as' => 'getPersons',
        'uses' => 'PersonController@getAll',
    ]);

    $router->post('saveTodayAddresses',  [
        'as' => 'saveTodayAddresses',
        'uses' => 'PersonController@saveTodayAddresses',
    ]);


});

$router->get('/{any:.*}', function () {
    return view('app');
});



