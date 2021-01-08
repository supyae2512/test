<?php

Route::get('/', [
    'middleware' => 'admin',
    'as'         => '/',
    'uses'       => 'Admin\ItemController@index'
]);

Route::group(['namespace' => 'Admin'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::get('login', [
            'as'   => 'auth/login',
            'uses' => 'AuthController@showLoginForm'
        ]);

        Route::post('login', [
            'as'   => 'auth/login',
            'uses' => 'AuthController@login'
        ]);

        Route::get('logout', [
            'as'   => 'auth/logout',
            'uses' => 'AuthController@logout'
        ]);

        Route::get('register', [
            'as'   => 'auth/register',
            'uses' => 'RegisterController@showRegisterForm'
        ]);

        Route::post('register', [
            'as'   => 'auth/register',
            'uses' => 'RegisterController@register'
        ]);
    });


    Route::group(['middleware' => 'admin','prefix' => 'items'], function () {


        Route::get('add-cart/{id}', 'CartController@addToCart');

        Route::get('checkout', 'CartController@checkout');

    });


});


Auth::routes();

