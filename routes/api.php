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

        Route::get('mealCategories', 'MealCategoryController@index');
        Route::get('mealCategories/create', 'MealCategoryController@create');
        Route::post('mealCategories', 'MealCategoryController@store');
        Route::get('mealCategories/{id}', 'MealCategoryController@show');
        Route::get('mealCategories/{id}/edit', 'MealCategoryController@edit');
        Route::put('mealCategories/{id}', 'MealCategoryController@update');
        Route::delete('mealCategories/{id}', 'MealCategoryController@destroy');

    });
});
