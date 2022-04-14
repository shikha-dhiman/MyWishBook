<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
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
/**
	*----------------------------------------------
	* ---  APIS ROUTES  ----  AUTH
	*----------------------------------------------
*/
Route::group([
		'namespace'=>'Auth',
	], function(){
		Route::match(['get', 'post'], '/register', 'UserauthController@register');
		Route::match(['get', 'post'], '/login', 'UserauthController@login');
	});
/**
	*----------------------------------------------
	* ---  APIS ROUTES  ----  DASHBOARD
	*----------------------------------------------
*/

Route::group([
		'namespace'=>'Dashboard',
	], function(){
		Route::get('/', 'dashboardController@index');
});

Route::get('/logout', function()
{
    Session::forget('username');
    return view('User.Auth.login');
});

/**
	*----------------------------------------------
	* ---  APIS ROUTES  ----  EVENTS
	*----------------------------------------------
*/
Route::group([
		'namespace'=>'Event',
		'prefix'=>'events'
	], function(){
		Route::get('/', 'eventsController@index')->name('events');
		Route::match(['get', 'post'], 'add', 'eventsController@add')->name('add');
		Route::match(['get', 'post'], 'edit', 'eventsController@edit')->name('edit');
		Route::post('delete', 'eventsController@delete');

});