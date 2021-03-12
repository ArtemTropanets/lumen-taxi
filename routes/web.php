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

    $router->get('getActive', [
        'uses' => 'PersonController@getActive',
    ]);

    $router->get('getAll', [
        'uses' => 'PersonController@getAll',
    ]);

    $router->post('create', [
        'uses' => 'PersonController@create',
    ]);

    $router->post('edit', [
        'uses' => 'PersonController@edit',
    ]);

    $router->delete('deactivate/{id}', [
        'uses' => 'PersonController@deactivate'
    ]);

    $router->post('activate', [
        'uses' => 'PersonController@activate',
    ]);

    $router->delete('delete/{id}', [
        'uses' => 'PersonController@delete',
    ]);

    $router->post('saveTodayAddresses', [
        'uses' => 'PersonController@saveTodayAddresses',
    ]);

});


$router->group(['prefix' => 'route'], function () use ($router) {

    $router->get('getRoutesForEdit', [
        'uses' => 'RouteController@getRoutesForEdit',
    ]);

    $router->post('saveRoutes', [
        'uses' => 'RouteController@saveRoutes',
    ]);

});


$router->get('/{any:.*}', function () {
    return view('app');
});



