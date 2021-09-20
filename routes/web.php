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

use App\Http\Controllers\PasteController;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@Login');


    // Public Paste URL
    $router->get('/paste/{slug}', 'PasteController@show');


    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->post('/logout', 'AuthController@logout');

        // Authenticated Paste URLs
        $router->post('/Paste', 'PasteController@create');
        $router->patch('/Paste/{slug}', 'PasteController@update');
        $router->delete('/Paste/{slug}', 'PasteController@destroy');

    });
});
