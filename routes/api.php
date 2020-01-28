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
            Route::get('/{user}', 'UserController@show')->middleware('can:view,user');
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
                Route::get('/', 'CompanyUserController@index')->middleware(['role:admin,producer_admin,customer_admin', 'can:index,company']);
                Route::post('/', 'CompanyUserController@store')->middleware('role:admin');
            });
        });

        Route::get('companies/{company}/meal-categories', 'MealCategoryController@index');
        Route::prefix('meal-categories')->group(function () {
            Route::post('/', 'MealCategoryController@store')->middleware('role:producer_admin');
            Route::get('{mealCategory}', 'MealCategoryController@show');
            Route::put('{mealCategory}', 'MealCategoryController@update')->middleware(['role:producer_admin', 'can:ifCompanyId,mealCategory']);
            Route::delete('{mealCategory}', 'MealCategoryController@destroy')->middleware(['role:producer_admin', 'can:ifCompanyId,mealCategory']);
        });

        Route::get('meal-categories/{mealCategory}/meals', 'MealController@index');
        Route::prefix('meals')->group(function () {
            Route::post('/', 'MealController@store')->middleware('role:producer_admin');
            Route::get('{meal}', 'MealController@show');
            Route::put('{meal}', 'MealController@update')->middleware(['role:producer_admin', 'can:ifCompanyId,meal']);
            Route::delete('{meal}', 'MealController@destroy')->middleware(['role:producer_admin', 'can:ifCompanyId,meal']);
        });

        Route::get('companies/{company}/producerOrders', 'OrderController@producerIndex')->middleware(['role:producer_admin,producer_user', 'can:view,company']);
        Route::get('companies/{company}/customerOrders', 'OrderController@customerIndex')->middleware(['role:customer_admin', 'can:view,company']);
        Route::prefix('orders')->group(function () {
            Route::post('/', 'OrderController@store');
            Route::get('{order}', 'OrderController@show')->middleware('can:view,order');
            Route::put('producer-update-status/{order}', 'OrderController@producerUpdateStatus')->middleware('role:producer_admin,producer_user', 'can:ifCompanyId,order');
            Route::put('cancel/{order}', 'OrderController@cancel')->middleware('can:cancel,order');
            Route::delete('{order}', 'OrderController@destroy')->middleware(['role:producer_admin', 'can:ifCompanyId,order']);

            Route::resource('{order}/payment', 'PaymentController')->middleware('can:view,order');
        });

        Route::resource('payment-methods', 'PaymentMethodController')->middleware('auth');
    });
});
