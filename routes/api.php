<?php

use App\User;
use Illuminate\Http\Request;

/* Route::group(['prefix' => 'auth'], function () {
   
    
}); */

Route::group(['middleware' => ['cors'], 'prefix' => 'auth',], function () {
    Route::post('/login', 'AuthController@login');
    Route::post('/logout', 'AuthController@logout');
    Route::post('/refresh', 'AuthController@refresh');
    Route::post('/me', 'AuthController@me');
    Route::post('/register', 'AuthController@register');
    Route::post('/findUser', 'AuthController@findUser');
});


Route::group(['middleware' => ['cors']], function () {
    Route::apiResource('/user', 'UserController', ['except' => ['store', 'create', 'edit']]);
    Route::apiResource('/category', 'CategoryController');
    Route::apiResource('/product', 'ProductController');
    Route::apiResource('/company', 'CompanyController');
    Route::apiResource('/order', 'OrderController');
});


Route::group(['middleware' => ['cors'], 'prefix' => 'admin', 'namespace' => 'Admin'], function () {
    //Route::apiResource('/user', 'UserController');
    Route::apiResource('/category', 'CategoryController', ['except' => ['update', 'show' , 'create']]);
    Route::post('/category/{category}', 'CategoryController@update');

    Route::apiResource('/product', 'ProductController', ['except' => ['update', 'show' , 'create']]);
    Route::post('/product/{product}', 'ProductController@update');

    Route::apiResource('/company', 'CompanyController', ['except' => ['update', 'show' , 'create']]);
    Route::post('/company/{company}', 'CompanyController@update');
    //Route::apiResource('/order', 'OrderController');
});