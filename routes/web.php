<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Home Routes
Route::get('/', 'HomeController@index');
Route::permanentRedirect('/home', '/')->name('home');
 
Route::get('/privacy-policy', 'HomeController@privacyPolicy')->name('privacy_policy');
Route::get('/terms-and-conditions', 'HomeController@termsAndConditions')->name('terms_and_conditions');


Auth::routes();

Route::get('logout', 'ErrorsController@abort404')->name('errors.404');

// Social Auth Routes
Route::get('auth/{provider}', 'Auth\\SocialAuthController@redirect')->name('social.auth');
Route::get('auth/{provider}/callback', 'Auth\\SocialAuthController@callback');

Route::namespace('Auth')->group(function () {
    Route::get('/seller-register', "SellersController@show")->name('seller.show');
});


// Languages Route
Route::get('/language', 'LanguageController@getLanguage')->name('get.language');
Route::get('/language/{lang}', 'LanguageController@changeLanguage')->name('change.language');


Route::middleware('auth')->group(function () {

    // Customer Routes
    Route::middleware('customer')->group(function () {
        // Favorites Products Profile Route
        Route::get('/profile/favorites', 'ProfileController@favorites')
            ->name('customers.profile.favorites');

        // user order's history Route
        Route::get('/profile/orders/history', 'ProfileController@myProductsOrders')
            ->name('customers.myProductsOrders');

        // Profile Route
        Route::get('/my-account', 'ProfileController@editProfile')
            ->name('customers.editProfile');

        // edit Profile Password Routes
        Route::get('/profile/edit/password', 'ProfileController@editPassword')
            ->name('profile.edit.password');

        Route::patch('/profile/update/password', 'ProfileController@updatePassword')
            ->name('profile.update.password');

        // Update Profile Routes
        Route::patch('/profile/update', 'ProfileController@updateProfile')
            ->name('customer.profile.update');

        // Update customer Routes
        Route::patch('/profile/address/update', 'ProfileController@updateAddress')
            ->name('customer.address.update');

        // Product Order Route
        Route::post('/products/{product}/order', 'OrderController@order')
            ->name('products.order');
    });
});
