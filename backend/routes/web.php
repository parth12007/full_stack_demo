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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function ($router) {
    $router->get('/crud/lists', ['as' => 'retailer-detail', 'uses' => 'CrudController@lists']);
    $router->post('/crud/add', ['as' => 'retailer-detail', 'uses' => 'CrudController@add']);
    $router->get('/crud/get/{id}', ['as' => 'retailer-detail', 'uses' => 'CrudController@get']);
    $router->put('/crud/update/{id}', ['as' => 'retailer-detail', 'uses' => 'CrudController@update']);
    $router->delete('/crud/delete/{id}', ['as' => 'retailer-detail', 'uses' => 'CrudController@delete']);
});
