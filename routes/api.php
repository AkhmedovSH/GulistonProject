<?php

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
    Route::get('/mainProjectStatistics', 'MainController@ProjectStatistics');

    Route::apiResource('/category', 'CategoryController', ['except' => ['update', 'create']]);
    Route::post('/categoryUpdate', 'CategoryController@update');

    Route::apiResource('/product', 'ProductController', ['except' => ['update', 'create']]);
    Route::post('/productUpdate', 'ProductController@update');

    Route::apiResource('/company', 'CompanyController', ['except' => ['update', 'create']]);
    Route::post('/companyUpdate', 'CompanyController@update');


    Route::apiResource('/order', 'OrderController', ['except' => ['store', 'create']]);
});
