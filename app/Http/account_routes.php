<?php
/**
 * Created by PhpStorm.
 * User: Rohit Modi
 * Date: 1/31/2017
 * Time: 12:35 PM
 */


Route::group(['prefix' => LaravelLocalization::getCurrentLocale(), 'before' => ['localization', 'before']], function () {
	Route::group(['middleware' => 'SentinelUser'], function () {

		/*
		 * Users Routes
		 */
		Route::get('/dashboard', 'UserController@dashboard');
		Route::post('/dashboard/editAccount', 'UserController@editAccount');
		Route::post('/dashboard/editInfo', 'UserController@editInfo');

		Route::get('/user/{id}/delete', 'UserController@delete');
		Route::post('/user/create', 'UserController@store');
		Route::post('/user/{id}/edit', 'UserController@edit');
		Route::post('/user/edit', 'UserController@edit');
		Route::patch('user/update/{user}', ['as'=> 'user.update', 'uses' => 'UserController@update']);
		Route::patch('userlocation/update/{user}', ['as'=> 'userlocation.update', 'uses' => 'UserLocationController@update']);
	});
});