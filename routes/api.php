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


Route::prefix('v1')->group(/**
 *
 */ function () {
    Route::get('/', function () {
        return $data = ['version' => 1];
    });

    Route::group(['namespace' => 'Api\\V1\\'], function () {
        Route::post('register', 'RegisterController@register');
        Route::post('login', 'LoginController@login');

        Route::prefix('companies')->group(function () {
            Route::get('/', 'CompanyController@index');
            Route::get('{company}', 'CompanyController@show');
            Route::post('/', 'CompanyController@store')->middleware('admin');
            Route::patch('{company}', 'CompanyController@update')->middleware('admin');
            Route::delete('{company}', 'CompanyController@destroy')->middleware('admin');

            Route::prefix('/{company}/users')->group(function () {
                Route::post('/', 'UserController@store');
            });
        });


    });

});




