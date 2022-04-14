<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
/**
	*----------------------------------------------
	* ---  APIS ROUTES  ----  AUTH
	*----------------------------------------------
*/
Route::group([
		'namespace'=>'Auth',
	], function(){
		Route::post('register', 'UserApiAuthController@register');
		Route::post('login', 'UserApiAuthController@login');
		Route::post('otpVerify', 'UserApiAuthController@otpVerification');
		Route::post('resendOTP', 'UserApiAuthController@resendOTP');
		Route::post('forgotPassword', 'UserApiAuthController@forgotPassword');
		Route::post('resetPassword', 'UserApiAuthController@resetPassword');
	});
/*********************API Route Events***********************/
Route::group([
		'namespace'=>'Event',
	], function(){
		Route::get('list','EventApiController@index');
		Route::post('add','EventApiController@add');
		Route::post('edit','EventApiController@edit');
		Route::delete('delete','EventApiController@delete');
	});