<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'visitors'], function() {
    Route::get('/login', 'Auth\LoginController@index');
    Route::post('/login', 'Auth\LoginController@postLogin');
    Route::get('/register', 'Auth\RegisterController@index');
    Route::post('/register', 'Auth\RegisterController@postReg');
    Route::get('/forgot-password', 'Auth\ForgotPasswordController@forgot');
    Route::post('/forgot-password', 'Auth\ForgotPasswordController@postForgot');
    Route::get('/reset/{email}/{code}', 'Auth\ForgotPasswordController@reset');
    Route::post('/reset/{email}/{code}', 'Auth\ForgotPasswordController@postReset');
});

Route::get('/activation/{email}/{code}', 'Auth\ActivationController@activation');
Route::post('/logout', 'Auth\LoginController@postLogout');

Route::group(['middleware' => 'admin', 'namespace' => 'Admin', 'prefix' => '/admin'], function() {
    Route::get('/', 'AdminController@index');
    
    Route::get('/users', 'UserController@index');
    Route::get('/users/add', 'UserController@add');
    Route::post('/users/add', 'UserController@postAdd');
    Route::get('/users/edit/{id}', 'UserController@edit');
    Route::post('/users/edit', 'UserController@postEdit');
    Route::post('/users/del', 'UserController@del');

    Route::get('/task-status', 'TaskStatusController@index');
    Route::get('/task-status/add', 'TaskStatusController@add');
    Route::post('/task-status/add', 'TaskStatusController@postAdd');
    Route::get('/task-status/edit/{id}', 'TaskStatusController@edit');
    Route::post('/task-status/edit', 'TaskStatusController@postEdit');
    Route::post('/task-status/del', 'TaskStatusController@del');

    Route::get('/projects', 'ProjectController@index');
    Route::get('/projects/add', 'ProjectController@add');
    Route::post('/projects/add', 'ProjectController@postAdd');
    Route::get('/projects/edit/{id}', 'ProjectController@edit');
    Route::post('/projects/edit', 'ProjectController@postEdit');
    Route::post('/projects/del', 'ProjectController@del');

    Route::get('/tasks', 'TaskController@index');
    Route::get('/tasks/add', 'TaskController@add');
    Route::post('/tasks/add', 'TaskController@postAdd');
    Route::get('/tasks/edit/{id}', 'TaskController@edit');
    Route::post('/tasks/edit', 'TaskController@postEdit');
    Route::post('/tasks/del', 'TaskController@del');

    Route::post('/tasks/filter', 'TaskController@filter');
    Route::post('/tasks/search', 'TaskController@search');
});

Route::group(['middleware' => 'user', 'namespace' => 'User', 'prefix' => '/user'], function() {
    Route::get('/', 'UserController@index');

    Route::get('/projects', 'ProjectController@index');
    Route::get('/projects/add', 'ProjectController@add');
    Route::post('/projects/add', 'ProjectController@postAdd');
    Route::get('/projects/edit/{id}', 'ProjectController@edit');
    Route::post('/projects/edit', 'ProjectController@postEdit');
    Route::post('/projects/del', 'ProjectController@del');

    Route::get('/tasks', 'TaskController@index');
    Route::get('/tasks/add', 'TaskController@add');
    Route::post('/tasks/add', 'TaskController@postAdd');
    Route::get('/tasks/edit/{id}', 'TaskController@edit');
    Route::post('/tasks/edit', 'TaskController@postEdit');
    Route::post('/tasks/del', 'TaskController@del');

    Route::post('/tasks/filter', 'TaskController@filter');
    Route::post('/tasks/search', 'TaskController@search');
});