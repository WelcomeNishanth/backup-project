<?php

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

Route::group([
    'prefix' => 'v1',
    'middleware' => ['auth0.jwt', 'auth0.jwt_verification', 'auth0.jwt_force', 'authtoken'],
        ], function () {

    Route::get('authenticate', function () {
        return Auth::user();
    });

    Route::post('requestquote', 'QuoteController@requestquote', function () {
        return Response::json(['statusCode' => '200', 'statusMessage' => 'Success', 'message' => 'User has been added successfully']);
    });

    Route::post('confirmorder', 'QuoteController@confirmorder', function () {
        return Response::json(['statusCode' => '200', 'statusMessage' => 'Success', 'message' => 'Shipment order accepted successfully']);
    });

    Route::post('acceptQuote', 'QuoteController@acceptQuote', function () {
        return Response::json(['statusCode' => '200', 'statusMessage' => 'Success', 'message' => 'Shipment order accepted successfully']);
    });

    Route::post('quotes', 'QuoteController@quotes');

    Route::get('quote', 'QuoteController@quote');

    Route::get('stats', 'QuoteController@stats');

    Route::get('userinfo', 'UserController@getTokenInfo');

    Route::get('getAddress', 'GeocodeController@getAddress');

    Route::get('getAddressInfo', 'GeocodeController@getAddressInfo');

    Route::post('validateAddress', 'GeocodeController@validateAddress');

    Route::post('companies', 'CompanyController@update');

    Route::get('companies', 'CompanyController@index');

    Route::post('users', 'UserController@update');

    Route::post('changePassword', 'UserController@changepassword');

    Route::get('users', 'UserController@index');

    Route::post('classcode', 'QuoteController@classCode');

    Route::get('downloadAgreement', 'CompanyController@downloadAgreement');

    Route::post('invoices', 'InvoiceController@getInvoices');

    Route::post('deliveries', 'DeliveryController@deliveries');

    Route::get('delivery', 'DeliveryController@delivery');

    Route::post('regenerateApiKey', 'UserController@regenerateApiKey');
});

Route::group([
    'prefix' => 'admin/v1',
    'middleware' => ['auth0API'],
        ], function () {

    Route::post('users', 'UserController@upsert');

    Route::post('companies', 'CompanyController@upsert');
});

Route::group([
    'prefix' => 'v1'
        ], function () {
    //Route::post('activateUser/{token}', 'UserController@activateUser');
    Route::post('resetPassword/{token}', 'UserController@resetpassword');
    Route::get('trackOrder', 'DeliveryController@trackOrder');
});

Route::group([
    'prefix' => 'v2',
    'middleware' => ['auth.apikey'],
        ], function () {
    Route::post('requestquote', 'QuoteController@requestquote', function () {
        return Response::json(['statusCode' => '200', 'statusMessage' => 'Success', 'message' => 'User has been added successfully']);
    });

    Route::post('acceptQuote', 'QuoteController@acceptQuote', function () {
        return Response::json(['statusCode' => '200', 'statusMessage' => 'Success', 'message' => 'Shipment order accepted successfully']);
    });
});


Route::group([
    'prefix' => 'v2'
        ], function () {
    Route::post('validate', 'UserController@validateApiKey');
});
