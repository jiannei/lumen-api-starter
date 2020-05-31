<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return app()->version();
});

Route::get('author', function () {
    $response = Http::withOptions(['timeout' => 3])->get('https://api.github.com/users/Jiannei');
    $response->throw();

    return $response->json();
});

Route::get('configurations', 'ExampleController@configurations');
Route::get('logs', 'ExampleController@logs');

Route::post('users', 'UsersController@store');
Route::get('users/{id}', 'UsersController@show');
Route::get('users', 'UsersController@index');

Route::post('auth/login', 'AuthController@login');
Route::delete('auth/logout', 'AuthController@logout');
Route::put('auth/refresh', 'AuthController@refresh');
Route::get('auth/user', 'AuthController@user');
