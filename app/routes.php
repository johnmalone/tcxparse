<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'HomeController@showHome');

Route::get('login', 'HomeController@showLogin');
Route::post('login',  array('before' => 'csrf', 'uses' =>'HomeController@doLogin'));

Route::get('logout', array('uses' => 'HomeController@doLogout'));

Route::get('user/{id}', function($userID)
{
	$user = User::findOrFail($userID);
	return View::make('user')->with('user', $user);
});

Route::get('upload', array('before' => 'auth', 'uses' => 'UploadController@showUpload'));
Route::post('upload', array('before' => 'auth', 'uses' => 'UploadController@doUpload'));

Route::get('uploadProgress', array('before' => 'auth', 'uses' => 'UploadController@getUploadProgress'));

Route::get('parsedActivityData/{id}/{type}', array('before' => 'auth', 'uses' => 'ParsedActivityDataController@getAjaxData'));

