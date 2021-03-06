<?php

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

Route::get('/', 'HomeController@home');

Route::get('login', [ 'as' => 'login', 'uses' => 'AuthController@login']);
Route::get('logout', [ 'as' => 'logout', 'uses' => 'AuthController@logout']);
Route::get('callback', 'AuthController@callback');

Route::get('/orders/all', 'HomeController@currentOrders');
Route::get('/orders/all/refresh', 'HomeController@refresh');

Route::get('/eve/open-market/type/{type_id}', 'HomeController@openMarket');
