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

Route::prefix('v1')->group(function () {
    Route::get('/', function () {
        return $data = ['version' => 1];
    });

    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::group(['namespace' => 'Api\\V1\\'], function () {
        Route::post('register', 'RegisterController@register');
        Route::post('login', 'LoginController@login');

        Route::get('meal-categories', 'MealCategoryController@index');
        Route::post('meal-categories', 'MealCategoryController@store')->middleware('role:producer_admin');
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
