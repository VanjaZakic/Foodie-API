<?php

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

Route::prefix('v1')->group(function () {
    Route::get('/', function () {
        return $data = ['version' => 1];
    });

    Route::group(['namespace' => 'Api\\V1\\'], function () {
        Route::post('login', 'LoginController@login');

        Route::prefix('users')->group(function () {
            Route::get('/', 'UserController@index')->middleware('role:admin');
            Route::get('/{user}', 'UserController@show');
            Route::post('/', 'UserController@store');
            Route::put('/{user}', 'UserController@update');
            Route::delete('/{user}', 'UserController@destroy')->middleware('role:admin');
        });

        Route::prefix('companies')->group(function () {
            Route::get('/', 'CompanyController@index');
            Route::get('{company}', 'CompanyController@show');
            Route::post('/', 'CompanyController@store')->middleware('role:admin');
            Route::put('{company}', 'CompanyController@update')->middleware('role:admin');
            Route::delete('{company}', 'CompanyController@destroy')->middleware('role:admin');

            Route::prefix('/{company}/users')->group(function () {
                Route::get('/', 'CompanyUserController@index')->middleware('role:admin,producer_admin,customer_admin');
                Route::post('/', 'CompanyUserController@store')->middleware('role:admin');
            });
        });

        Route::get('companies/{company}/meal-categories', 'MealCategoryController@index');
        Route::post('companies/{company}/meal-categories', 'MealCategoryController@store')->middleware('role:producer_admin');
        Route::get('meal-categories/{mealCategory}', 'MealCategoryController@show');
        Route::put('meal-categories/{mealCategory}', 'MealCategoryController@update')->middleware('role:producer_admin');
        Route::delete('meal-categories/{mealCategory}', 'MealCategoryController@destroy')->middleware('role:producer_admin');

        Route::get('meal-categories/{mealCategory}/meals', 'MealController@index');
        Route::post('meals', 'MealController@store')->middleware('role:producer_admin');
        Route::get('meals/{meal}', 'MealController@show');
        Route::put('meals/{meal}', 'MealController@update')->middleware('role:producer_admin');
        Route::delete('meals/{meal}', 'MealController@destroy')->middleware('role:producer_admin');

    });
});
