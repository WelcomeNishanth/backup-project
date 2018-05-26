<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | This file is where you may define all of the routes that are handled
  | by your application. Just tell Laravel the URIs it should respond
  | to using a Closure or controller method. Build something great!
  |
 */

Route::get('/', function () {
    return view('gateway', ['authDomain' => getenv('OAUTH_BASE_DOMAIN'), 'authId' => getenv('OAUTH_BASE_CLIENT_ID')]);
});

Route::get('/logout', function() {
    Auth::logout();
    return redirect('/');
});

Route::get('/home', function () {
    return view('home');
});

Route::post('/api/v1/activateUser/{token}', 'UserController@activateUser');

Route::post('/api/v1/resendActivation/', 'UserController@resendActivationEmail');

Route::get('/activate/{key}', 'SignupController@activationForm');

// forget password
Route::post('/api/v1/forgotPwd/', 'UserController@forgotPassword');

Route::post('/api/v1/resendPwdEmail/', 'UserController@resendPwdEmail');

Route::post('/api/v1/resetpwd', 'UserController@resetPassword');

Route::get('/api/v1/resetpwd', function () {
    return view('resetconfirm', [
        'authDomain' => getenv('OAUTH_BASE_DOMAIN'),
        'cdnlocation' => getenv('CDN_LOCATION'),
        'authId' => getenv('OAUTH_BASE_CLIENT_ID'),
        'hasError' => false,
        'errorMessage' => null]);
});

//api/v1/companies
Route::resource('/api/v1/signup', 'SignupController@index');

Route::get('/account/forgot', function () {
    return view('forgotpasswordemail');
});

Route::get('/resetpassword/{key}', 'SignupController@resetPassword');
Route::get('/login', function () {
    return view('login', ['cdnlocation' => getenv('CDN_LOCATION'), 'authDomain' => getenv('OAUTH_BASE_DOMAIN'), 'authId' => getenv('OAUTH_BASE_CLIENT_ID')]);
});

Route::get('/notfound', function () {
    $url = \Illuminate\Support\Facades\URL::to('/');

    $errorMessageHead = trans('messages.not_found_header');
    $errorMessage = trans('messages.not_found_message', ['name' => $url]);
    
    return view('404', ['errorMessageHead' => $errorMessageHead, 'errorMessage' => $errorMessage]);
});


Route::get('/policy/terms', function () {
    return view('terms');
});

Route::get('/inactive', function () {
    return view('inactiveuser');
});

Route::get('/news', function () {
    $url = \Illuminate\Support\Facades\URL::to('/');
    return view('newsroom', ['url' => $url]);
});

Route::get('/tracking/{trackId}', function ($trackId) {
   return view('tracking', ['cdnlocation' => getenv('CDN_LOCATION'), 'authDomain' => getenv('OAUTH_BASE_DOMAIN'), 'authId' => getenv('OAUTH_BASE_CLIENT_ID'), 'allowedConnection' => getenv('AUTH0_GATEWAY_CONNECTION_NAME'), 'trackId' => $trackId]);
});

Route::get('/tracking', function () {
   return view('tracking', ['cdnlocation' => getenv('CDN_LOCATION'), 'authDomain' => getenv('OAUTH_BASE_DOMAIN'), 'authId' => getenv('OAUTH_BASE_CLIENT_ID'), 'allowedConnection' => getenv('AUTH0_GATEWAY_CONNECTION_NAME'), 'trackId' => '']);
});

