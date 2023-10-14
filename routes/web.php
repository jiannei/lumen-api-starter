<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

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

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('users[/{paginate}]', 'UsersController@index');

$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('login', 'AuthController@login');
    $router->delete('logout', 'AuthController@logout');
    $router->put('refresh', 'AuthController@refresh');
    $router->get('me', 'AuthController@me');
});