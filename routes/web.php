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

Route::get('/redirect', 'UsersController@redirect');
Route::get('/callback', 'UsersController@callback');

Route::get('login/github', 'UsersController@redirectToProvider');
Route::get('login/github/callback', 'UsersController@handleProviderCallback');