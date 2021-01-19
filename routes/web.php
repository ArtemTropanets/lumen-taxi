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
    $router->get('getAll', [
        'as' => 'getPersons',
        'uses' => 'PersonController@getAll',
    ]);

    $router->post('create', [
        'as' => 'create',
        'uses' => 'PersonController@create',
    ]);

    $router->post('edit', [
        'as' => 'edit',
        'uses' => 'PersonController@edit',
    ]);

    $router->delete('softDelete/{id}', [
        'as' => 'softDelete',
        'uses' => 'PersonController@softDelete'
    ]);

    $router->post('restore', [
        'as' => 'restore',
        'uses' => 'PersonController@restore',
    ]);

    $router->post('saveTodayAddresses', [
        'as' => 'saveTodayAddresses',
        'uses' => 'PersonController@saveTodayAddresses',
    ]);
});


$router->group(['prefix' => 'route'], function () use ($router) {
    $router->get('getRoutesForEdit', [
        'as' => 'getRoutesForEdit',
        'uses' => 'RouteController@getRoutesForEdit',
    ]);

    $router->post('saveRoutes', [
        'as' => 'saveRoutes',
        'uses' => 'RouteController@saveRoutes',
    ]);
});


$router->get('/{any:.*}', function () {
    return view('app');
});



