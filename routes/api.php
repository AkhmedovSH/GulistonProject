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
    Route::delete('/userFavoriteDelete', 'UserController@userFavoriteDelete');
    Route::post('/userUpdate', 'UserController@update');
    Route::get('/userAddress', 'UserController@userAddress');
    Route::post('/userAddressAdd', 'UserController@userAddressAdd');
    Route::post('/userAddressUpdate', 'UserController@userAddressUpdate');
    Route::delete('/userDestroy', 'UserController@destroy');

    Route::get('/getCategories', 'CategoryController@getCategories');
    
    Route::get('/getCategoryProducts/{category_id}', 'CategoryController@getCategoryProducts');

    Route::get('/getAdvertising', 'AdvertisingController@getAdvertising');

    Route::get('/productAll', 'ProductController@productAll');
    Route::get('/product/{id}', 'ProductController@show');
    Route::get('/productTopHome', 'ProductController@productTopHome');
    Route::get('/productMostFamous', 'ProductController@productMostFamous');
    Route::get('/productMostSaled', 'ProductController@productMostSales');
    Route::post('/productSearch', 'ProductController@productSearch');
    Route::post('/productFeedback', 'ProductController@addFeedback');

    Route::get('/getCompanies', 'CompanyController@getCompanies');
    Route::get('/companyCategories', 'CompanyController@companyCategories');
    Route::get('/oneCompanyCategories/{company_id}', 'CompanyController@oneCompanyCategories');
    Route::get('/companyCategoryProducts/{company_id}', 'CompanyController@companyCategoryProducts');

    Route::get('/getCart', 'OrderController@getCart');
    Route::post('/cartAdd', 'OrderController@cartAdd');
    Route::post('/cartUpdate', 'OrderController@cartUpdate');
    Route::delete('/cartDeleteOne/{id}', 'OrderController@cartDeleteOne');
    Route::delete('/cartDeleteAll', 'OrderController@cartDeleteAll');
    
    Route::get('/getOrders', 'OrderController@getOrders');
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
