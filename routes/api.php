<?php

use App\User;
use Illuminate\Http\Request;

Route::group(['prefix' => 'auth'], function () {
   
    Route::post('/login', 'AuthController@login');
    Route::post('/logout', 'AuthController@logout');
    Route::post('/refresh', 'AuthController@refresh');
    Route::post('/me', 'AuthController@me');
    Route::post('/register', 'AuthController@register');
    Route::post('/findUser', 'AuthController@findUser');
});




Route::apiResource('/user', 'UserController');
Route::apiResource('/category', 'CategoryController');
Route::apiResource('/product', 'ProductController');
Route::apiResource('/company', 'CompanyController');
Route::apiResource('/order', 'OrderController');