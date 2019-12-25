<?php

use Illuminate\Support\Facades\Route;

Route::post('/user/projects', [
    'middleware' => ['auth', 'xss', 'https'],
    'uses' => 'App\Http\Controllers\ProjectsController@projects'
]);

Route::post('/user/project/remove', [
    'middleware' => ['auth', 'xss', 'https'],
    'uses' => 'App\Http\Controllers\ProjectsController@remove'
]);

Route::post('/user/project/add', [
    'middleware' => ['auth', 'xss', 'https'],
    'uses' => 'App\Http\Controllers\ProjectsController@add'
]);

Route::post('/user/project/get', [
    'middleware' => ['auth', 'xss', 'https'],
    'uses' => 'App\Http\Controllers\ProjectsController@get'
]);

Route::post('/user/project/update', [
    'middleware' => ['auth', 'xss', 'https'],
    'uses' => 'App\Http\Controllers\ProjectsController@update'
]);
