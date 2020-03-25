<?php

Route::group(['middleware' => ['cors'], 'prefix' => 'auth'], function () {
    Route::post('/login', 'AuthController@login');
    Route::post('/loginTaxi', 'AuthController@loginTaxi');
    Route::post('/logout', 'AuthController@logout');
    Route::post('/refresh', 'AuthController@refresh');
    Route::post('/me', 'AuthController@me');

    Route::post('/checkAdmin', 'AuthController@checkAdmin');
    //Route::post('/register', 'AuthController@register');
});



Route::group(['middleware' => ['cors']], function () {
    Route::get('/redis_test', 'MainController@redis_test');
    Route::get('/getGeneralSetting', 'MainController@getGeneralSetting');
   
    Route::get('/getOrdersTaxi', 'Taxi\OrderTaxiController@getOrdersTaxi');
    Route::get('/getTaxiDriverOrders', 'Taxi\OrderTaxiController@getTaxiDriverOrders');
    Route::post('/createOrderTaxi', 'Taxi\OrderTaxiController@createOrderTaxi');
    Route::post('/acceptOrderTaxi', 'Taxi\OrderTaxiController@acceptOrderTaxi');
    Route::post('/getDirection', 'Taxi\OrderTaxiController@getDirection');

    Route::get('/deliveryTable', 'MainController@deliveryTable');
    
    Route::post('/setFirebaseToken', 'UserController@setFirebaseToken');
    Route::get('/userShow', 'UserController@userShow');
    Route::get('/userFavorite', 'UserController@userFavorite');
    Route::post('/userFavoriteAdd', 'UserController@userFavoriteAdd');
    Route::delete('/userAddressDelete/{id}', 'UserController@userAddressDelete');
    Route::delete('/userFavoriteDelete/{product_id}', 'UserController@userFavoriteDelete');
    Route::post('/userUpdate', 'UserController@update');
    Route::get('/userAddress', 'UserController@userAddress');
    Route::post('/userAddressAdd', 'UserController@userAddressAdd');
    Route::post('/userAddressUpdate', 'UserController@userAddressUpdate');
    Route::post('/userAddressDelete/{id}', 'UserController@userAddressDelete');
    Route::post('/userRequestToAdmin', 'UserController@userRequestToAdmin'); //need delete when new api will be used for chat with admin
    Route::delete('/userDestroy', 'UserController@destroy');

    /* ADMIN USER CHAT */
    Route::post('/createChatSubject', 'FeedbackController@storeFeedback');
    Route::get('/getChatSubjects', 'FeedbackController@index');
    Route::get('/getChatMessages/{id}', 'FeedbackController@show');
    Route::post('/sendMessageToAdmin', 'FeedbackController@storeFeedbackMessage');
    Route::post('/subjectIsRead', 'FeedbackController@isRead');

    Route::apiResource('/userCard', 'UserCardController', ['except' => ['update', 'create']]);
    Route::post('/userCardUpdate', 'UserCardController@update');

    Route::get('/getCategories', 'CategoryController@getCategories');
    Route::get('/getCategoryProducts/{category_id}', 'CategoryController@getCategoryProducts');
    Route::get('/getCompanyCategoryProducts/{company_category_id}', 'CategoryController@getCompanyCategoryProducts');

    Route::get('/getAdvertising', 'AdvertisingController@getAdvertising');

    Route::get('/productAll', 'ProductController@productAll');
    Route::get('/product/{id}', 'ProductController@show');
    Route::get('/productTopHome', 'ProductController@productTopHome');
    Route::get('/productFamous', 'ProductController@productFamous');
    Route::get('/productDiscount', 'ProductController@productDiscount');
    Route::get('/productRandom', 'ProductController@productRandom');
    Route::post('/productSearch', 'ProductController@productSearch');

    Route::get('/getCompanies', 'CompanyController@getCompanies');
    Route::get('/companyCategories', 'CompanyController@companyCategories');
    Route::get('/oneCompanyCategories/{company_id}', 'CompanyController@oneCompanyCategories');
    Route::get('/companyCategoryProducts/{company_category_id}', 'CompanyController@companyCategoryProducts');

    Route::get('/getCart', 'OrderController@getCart');
    Route::post('/cartAdd', 'OrderController@cartAdd');
    Route::post('/cartUpdate', 'OrderController@cartUpdate');
    Route::delete('/cartDeleteOne/{id}', 'OrderController@cartDeleteOne');
    Route::delete('/cartDeleteAll', 'OrderController@cartDeleteAll');
    
    Route::get('/getOrders', 'OrderController@getOrders');
    Route::post('/orderCreate', 'OrderController@orderCreate');
    Route::post('/orderAccepted', 'OrderController@orderAccepted');
    Route::post('/orderRejected', 'OrderController@orderRejected');

    Route::post('/checkTransaction', 'TransactionController@checkTransaction');
    Route::post('/performTransaction', 'TransactionController@performTransaction');
});


Route::group(['middleware' => ['cors'], 'prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Route::get('/mainProjectStatistics', 'MainController@ProjectStatistics');
    Route::apiResource('/user', 'UserController');
    Route::post('/userUpdate', 'UserController@userUpdate');
    Route::post('/userSendNotificationToOne', 'UserController@userSendNotificationToOne');
    Route::post('/userSendNotificationToAll', 'UserController@userSendNotificationToAll');

    Route::get('/generalSetting', 'GeneralSettingController@index');
    Route::get('/generalSetting/{id}', 'GeneralSettingController@show');
    Route::post('/generalSettingUpdate', 'GeneralSettingController@update');


    Route::apiResource('/category', 'CategoryController', ['except' => ['update', 'create']]);
    Route::get('/categoryPluck', 'CategoryController@categoryPluck');
    Route::post('/categoryUpdate', 'CategoryController@update');

    Route::apiResource('/advertising', 'AdvertisingController', ['except' => ['update', 'create']]);
    Route::post('/advertisingUpdate', 'AdvertisingController@update');

    Route::apiResource('/product', 'ProductController', ['except' => ['update', 'create']]);
    Route::post('/productUpdate', 'ProductController@update');
    Route::get('/productSearch', 'ProductController@productSearch');

    Route::apiResource('/productColor', 'ProductColorController', ['except' => ['update', 'create']]);
    Route::post('/productColorUpdate', 'ProductColorController@update');

    Route::get('/transaction', 'TransactionController@index');
    Route::get('/transactionSearch', 'TransactionController@transactionSearch');

    Route::apiResource('/company', 'CompanyController', ['except' => ['update', 'create']]);
    Route::post('/companyUpdate', 'CompanyController@update');

    Route::apiResource('/companyCategory', 'CompanyCategoryController', ['except' => ['update', 'create']]);
    Route::post('/companyCategoryUpdate', 'CompanyCategoryController@update');
    Route::get('/getCompanyCategories/{company_id}', 'CompanyCategoryController@getCompanyCategories');

    Route::apiResource('/order', 'OrderController', ['except' => ['edit', 'create']]);
    Route::get('/orderSearch', 'OrderController@orderSearch');

    Route::get('/orderTaxi', 'OrderTaxiController@index');
    Route::get('/orderTaxiSearch', 'OrderTaxiController@orderTaxiSearch');

    Route::get('/adminFeedback', 'AdminFeedbackController@index');
    Route::post('/adminFeedback', 'AdminFeedbackController@store');
    Route::get('/adminFeedbackShow/{id}', 'AdminFeedbackController@show');
    Route::delete('/adminFeedbackDestroy/{id}', 'AdminFeedbackController@destroy');

    
    Route::apiResource('/deliveryClosedTime', 'DeliveryTimeController');
    Route::post('/deliveryClosedTimeUpdate', 'DeliveryTimeController@update');

    Route::get('/deliveryTimes', 'DeliveryTimeController@deliveryTimes');
});
