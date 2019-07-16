<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('v1/login', "Api\V1\LoginController@login")->name('login');
Route::post('v1/logout', "Api\V1\LoginController@logout")->middleware('auth:api')->name('logout');

Route::middleware('auth:api')->prefix('v1')->name('api.')->namespace('Api\V1')->group(function(){

	Route::prefix('user')->name('user.')->group(function(){
		Route::get('/', "UserController@index")->name('index');
		Route::get('/{user}', "UserController@show")->name('show');
		Route::post('/{user}', "UserController@store")->name('store');
		Route::put('/{user}', "UserController@update")->name('update');
		Route::delete('/{user}', "UserController@delete")->name('delete');
	});

	Route::prefix('group')->name('group.')->group(function(){
		Route::get('/', "GroupController@index")->name('index');
		Route::get('/{group}', "GroupController@show")->name('show');
		Route::post('/{group}', "GroupController@store")->name('store');
		Route::put('/{group}', "GroupController@update")->name('update');
		Route::delete('/{group}', "GroupController@delete")->name('delete');
	});

	Route::prefix('membership')->name('membership.')->group(function(){
		Route::post('/{group}', "GroupMembershipController@store")->name('store');
		Route::put('/{group}', "GroupMembershipController@update")->name('update');
	});

});