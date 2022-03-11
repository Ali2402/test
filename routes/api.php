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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/** ROUTING FOR CLIENT API */

Route::group(['prefix' => '/v1'] , function()
{

    Route::get('/domain' , 'ClientController@validate_domain');
    Route::get('/signature' , 'ClientController@validate_signature');
    Route::get('/user','ClientController@getUser');
    Route::get('/install','ClientController@install');
});

Route::group(['prefix' => '/domain'] , function()
{
    Route::get('/stats' , 'ClientController@get_stats');
    Route::get('/logs' , 'ClientController@get_logs');
    Route::get('/visit','ClientController@get_link');
    Route::get('/reset','ClientController@get_reset');


});