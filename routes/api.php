<?php

Route::group(['middleware' => ['cors'], 'prefix' => 'auth',], function () {
    Route::post('/login', 'AuthController@login');
    Route::post('/logout', 'AuthController@logout');
    Route::post('/refresh', 'AuthController@refresh');
    Route::post('/me', 'AuthController@me');
    //Route::post('/register', 'AuthController@register');
});


Route::group(['middleware' => ['cors']], function () {
    Route::get('/userShow', 'UserController@userShow');
    Route::get('/userFavorite', 'UserController@userFavorite');
    Route::post('/userFavoriteAdd', 'UserController@userFavoriteAdd');
    Route::post('/userUpdate', 'UserController@update');
    Route::get('/userAddress', 'UserController@userAddress');
    Route::post('/userAddressAdd', 'UserController@userAddressAdd');
    Route::post('/userAddressUpdate', 'UserController@userAddressUpdate');
    Route::delete('/userDestroy', 'UserController@destroy');

    Route::get('/category', 'CategoryController@index');
    
    Route::get('/getCategoryProducts/{category_id}', 'CategoryController@getCategoryProducts');

    Route::get('/advertising', 'AdvertisingController@index');

    Route::get('/product/{id}', 'ProductController@show');
    Route::get('/productByCategories', 'ProductController@productByCategories');
    Route::post('/productSearch', 'ProductController@productSearch');
    Route::post('/productFeedback', 'ProductController@addFeedback');

    Route::get('/company', 'CompanyController@index');
    Route::get('/companiesCategories', 'CompanyController@companiesCategories');
    Route::get('/oneCompanyCategories/{company_id}', 'CompanyController@oneCompanyCategories');
    Route::get('/companyCategoryProducts/{company_id}', 'CompanyController@companyCategoryProducts');

    Route::get('/cart', 'OrderController@cartIndex');
    Route::post('/cartAdd', 'OrderController@cartAdd');
    Route::post('/cartUpdate', 'OrderController@cartUpdate');
    Route::delete('/cartDestroyOne/{id}', 'OrderController@cartDestroyOne');
    Route::delete('/cartDestroyAll', 'OrderController@cartDestroyAll');
    
    Route::get('/orderIndex', 'OrderController@orderIndex');
    Route::post('/orderCreate', 'OrderController@orderCreate');
    Route::post('/orderAccepted', 'OrderController@orderAccepted');
    Route::post('/orderRejected', 'OrderController@orderRejected');
});


Route::group(['middleware' => ['cors'], 'prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Route::get('/mainProjectStatistics', 'MainController@ProjectStatistics');
    Route::apiResource('/user', 'UserController');

    Route::apiResource('/category', 'CategoryController', ['except' => ['update', 'create']]);
    Route::post('/categoryUpdate', 'CategoryController@update');

    Route::apiResource('/advertising', 'AdvertisingController', ['except' => ['update', 'create']]);
    Route::post('/advertisingUpdate', 'AdvertisingController@update');

    Route::apiResource('/product', 'ProductController', ['except' => ['update', 'create']]);
    Route::post('/productUpdate', 'ProductController@update');
    Route::get('/productSearch', 'ProductController@productSearch');

    Route::apiResource('/company', 'CompanyController', ['except' => ['update', 'create']]);
    Route::post('/companyUpdate', 'CompanyController@update');

    Route::apiResource('/companyCategory', 'CompanyCategoryController', ['except' => ['update', 'create']]);
    Route::post('/companyCategoryUpdate', 'CompanyCategoryController@update');

    Route::apiResource('/order', 'OrderController', ['except' => ['edit', 'create']]);
    Route::get('/orderSearch', 'OrderController@orderSearch');

    Route::apiResource('/adminFeedback', 'AdminFeedbackController', ['except' => ['edit', 'create']]);
});
