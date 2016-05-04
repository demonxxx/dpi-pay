<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use Illuminate\Http\Request;
Route::auth();
// Route::group(['middleware' => 'web'], function () {
	Route::group(['middleware' => 'auth'], function () {
		Route::get('/', 'HomeController@index');
		Route::get('/shop', 'ShopsController@index')->name("shops");
		Route::get('/shop/create', 'ShopsController@create');
		Route::post('/shop/store', 'ShopsController@store');
		Route::post('/shop/load_list','ShopsController@load_list');
		Route::get('/shop/{user_id}/edit','ShopsController@edit');
		Route::post('/shop/{id}/update','ShopsController@update');
                
                Route::get('/shipper', 'ShippersController@index')->name("shippers");
                Route::get('/shipper/create', 'ShippersController@create');
                Route::post('/shipper/store', 'ShippersController@store');
                Route::get('/shipper/{shipper_id}/edit','ShippersController@edit');
                Route::post('/shipper/{id}/update','ShippersController@update');
                Route::post('/shipper/load_list','ShippersController@load_list');
                
	});
// });


