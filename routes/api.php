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
    Route::get('/userShow', 'UserController@userShow');
    Route::post('/userUpdate', 'UserController@update');
    Route::post('/userAddressAdd', 'UserController@userAddressAdd');
    Route::post('/userAddressUpdate', 'UserController@userAddressUpdate');
    Route::delete('/userDestroy', 'UserController@destroy');

    Route::get('/category', 'CategoryController@index');

    Route::get('/advertising', 'AdvertisingController@index');

    Route::apiResource('/product', 'ProductController');
    Route::post('/productFeedback', 'ProductController@addFeedback');

    Route::apiResource('/company', 'CompanyController');

    Route::get('/cart', 'OrderController@cartIndex');
    Route::post('/cartAdd', 'OrderController@cartAdd');
    Route::post('/cartUpdate', 'OrderController@cartUpdate');
    Route::delete('/cartDestroyOne/{id}', 'OrderController@cartDestroyOne');
    Route::delete('/cartDestroyAll', 'OrderController@cartDestroyAll');
    Route::get('/orderIndex', 'OrderController@orderIndex');
    Route::post('/order', 'OrderController@order');
});


Route::group(['middleware' => ['cors'], 'prefix' => 'admin', 'namespace' => 'Admin'], function () {
    //Route::apiResource('/user', 'UserController');
    Route::get('/mainProjectStatistics', 'MainController@ProjectStatistics');

    Route::apiResource('/category', 'CategoryController', ['except' => ['update', 'create']]);
    Route::post('/categoryUpdate', 'CategoryController@update');

    Route::apiResource('/advertising', 'AdvertisingController', ['except' => ['update', 'create']]);
    Route::post('/advertisingUpdate', 'AdvertisingController@update');

    Route::apiResource('/product', 'ProductController', ['except' => ['update', 'create']]);
    Route::post('/productUpdate', 'ProductController@update');

    Route::apiResource('/company', 'CompanyController', ['except' => ['update', 'create']]);
    Route::post('/companyUpdate', 'CompanyController@update');


    Route::apiResource('/order', 'OrderController', ['except' => ['edit', 'create']]);
});
