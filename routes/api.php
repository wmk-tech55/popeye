<?php

// Authentications Routes
Route::namespace('Auth')->group(function () {
    Route::post('/login', 'ApiLoginController@login')->name('login');
    Route::post('/register', 'ApiRegisterController@register')->name('register');
    Route::post('/check-phone-availability', 'ApiRegisterController@checkPhoneAvailability')->name('check_phone_availability');
    Route::post('/check-email-availability', 'ApiRegisterController@checkEmailAvailability')->name('check_email_availability');
    Route::patch('/password/reset-by-email', 'ApiForgetPasswordController@resetByEmail')->name('password.reset.by_email');
    Route::patch('/password/reset-by-phone', 'ApiForgetPasswordController@resetByPhone')->name('password.reset.by_phone');
    // Social Auth
    Route::post('/social/process', 'ApiSocialController@process')->name('social.process');


    //forget passport 

    Route::post('/forget/password', 'ForgotPasswordController@sendResetLinkEmail')->name('sendResetLinkEmail');
    Route::post('/forget/password/reset', 'ForgotPasswordController@resetPassword')->name('resetPassword');
    Route::post('/forget/password/new', 'ProfileController@resetPassword')->name('resetPasswordNew');

    Route::middleware('auth:api')->group(function () {
        Route::post('/profile/update/logo', 'ProfileController@updateLogo')->name('profile.update.logo');
        Route::post('/profile/update/commercial-license', 'ProfileController@updateCommercialLicense')->name('profile.update.commercial_license');
        Route::post('/profile/update/vehicle-photos', 'ProfileController@updateVehiclePhotos')->name('profile.update.vehicle_photos');
        Route::patch('/profile/update/password', 'ProfileController@updatePassword')->name('profile.update.password');
        Route::patch('/profile/update/availability-status', 'ProfileController@updateAvailabilityStatus')->name('profile.availability_status');
        Route::patch('/profile/update', 'ProfileController@updateProfile')->name('profile.update');
        Route::delete('profile/delete-account', 'ProfileController@deleteAccount')->name('profile.delete_account');
        Route::patch('/profile/update/location', 'ProfileController@updateLocation')->name('profile.update_location');
        Route::get('/profile/history', 'ProfileController@history')->name('profile.show.history');
        Route::get('/profile', 'ProfileController@profile')->name('profile');
        Route::patch('/profile/update/firebase-cloud-messaging-token', 'ProfileController@updateFirebaseCloudMessagingToken')->name('profile.update_updateFirebaseCloudMessagingToken');
        Route::post('/logout', 'ApiLogoutController@logout')->name('logout');


        // customer orders history
        Route::get('/customer/profile/orders/delivered', 'ProfileController@customerDeliveredOrders')->name('customer.profile.show.customer_delivered_orders');
        Route::get('/customer/profile/orders/active', 'ProfileController@customerActiveOrders')->name('customer.profile.show.customer_active_orders');
        Route::get('/customer/profile/orders/canceled', 'ProfileController@customerCanceledOrders')->name('customer.profile.show.customer_canceled_orders');
        Route::get('/customer/profile/orders/approved', 'ProfileController@customerApprovedOrders')->name('customer.profile.show.customer_approved_orders');
       
        // driver orders history
        Route::get('/driver/profile/orders/delivered', 'ProfileController@driverDeliveredOrders')->name('driver.profile.show.driver_delivered_orders');
        Route::get('/driver/profile/orders/active', 'ProfileController@driverActiveOrders')->name('driver.profile.show.driver_active_orders');
        Route::get('/driver/profile/orders/not-accepted-requests', 'ProfileController@driverOrdersRequests')->name('driver.profile.show.driver_orders_requests');
        

        // provider orders history 
        Route::get('/store/profile/orders/approved', 'ProfileController@storeAccomplishedOrders')->name('store.profile.show.store_accomplished_orders');
        Route::get('/store/profile/orders/not-approved', 'ProfileController@storeRunningOrders')->name('store.profile.show.store_running_orders');
        Route::get('/store/profile/orders/delivered', 'ProfileController@storeDeliveredOrders')->name('store.profile.show.store_delivered_orders');
        Route::get('/store/profile/orders/canceled', 'ProfileController@storeCanceledOrders')->name('store.profile.show.store_canceled_orders');
        // provider working time
        Route::get('/store/profile/working-times/default', 'WorkTimesController@getDefaultTimes')->name('store.profile.working_times.default');
        Route::post('/store/profile/working-times/set', 'WorkTimesController@setWorkingTimes')->name('store.profile.working_times.set');


        // orders notifications   
        Route::get('orders/notifications', 'OrdersNotificationsController@showAll')->name('orders.notifications.show_All');
        Route::get('orders/notifications/{notification}', 'OrdersNotificationsController@show')->name('orders.notifications.show');

        // addresses Routes
        Route::post('/user/addresses', 'AddressesController@store')->name('user.addresses.store');
        Route::get('/user/addresses', 'AddressesController@index')->name('user.addresses.index');
        Route::patch('/user/addresses/update/{address}', 'AddressesController@update')->name('user.addresses.update');
        Route::delete('/user/addresses/destroy/{address_id}', 'AddressesController@destroy')->name('user.addresses.destroy');
    });
});

// Countries Routes
Route::group([], function () {
    Route::get('/countries/{country}/products', 'CountriesController@products')->name('countries.show.products');
    Route::get('/countries/{country}/cities', 'CountriesController@cities')->name('countries.show.cities');
    Route::get('/countries/{country}', 'CountriesController@show')->name('countries.show');
    Route::get('/countries', 'CountriesController@index')->name('countries.index');
});

// Cities Routes
Route::group([], function () {
    Route::get('/cities/{city}/products', 'CitiesController@products')->name('cities.show.products');
    Route::get('/cities/{city}/country', 'CitiesController@country')->name('cities.show.country');
    Route::get('/cities/{city}', 'CitiesController@show')->name('cities.show');
    Route::get('/cities', 'CitiesController@index')->name('cities.index');
});


// Categorizations  Routes
Route::group([], function () {

    Route::get('/categorizations/{categorization}', 'CategorizationsController@show')->name('categorizations.show');
    Route::get('/categorizations/{categorization}/products', 'CategorizationsController@products')->name('categorizations.list.products');
    Route::get('/categorizations/{categorization}/stores', 'CategorizationsController@stores')->name('categorizations.list.stores');
    Route::get('/categorizations', 'CategorizationsController@index')->name('categorizations.index');
});


// categories  Routes
Route::group([], function () {
    Route::get('/categories/{category}', 'CategoriesController@show')->name('categories.show');
    Route::get('/categories/{category}/products', 'CategoriesController@products')->name('categories.list.products');
    Route::get('/categories/by-store-categorization/{categorization_id}', 'CategoriesController@index')->name('categories.index');
});

// stores  Routes
Route::group([], function () {
    Route::get('/stores', 'CompaniesController@index')->name('stores.index');
    Route::get('/stores/{store}', 'CompaniesController@show')->name('stores.show');
    Route::get('/stores/{store}/published-products', 'CompaniesController@publishedProducts')->name('stores.published.products');
    Route::get('/stores/{store}/products-has-discount', 'CompaniesController@productsHasDiscount')->name('stores.products_has_discount');
    Route::get('/stores/{store}/all-products', 'CompaniesController@allProducts')->name('stores.list.products');
});

// drivers  Routes
Route::group([], function () {
    Route::get('/drivers', 'DriversController@index')->name('drivers.index');
    Route::get('/drivers/{driver}', 'DriversController@show')->name('drivers.show');
});



// Banners Routes
Route::group([], function () {

    Route::get('/banners', 'BannersController@index')->name('banners.index');
});


// Search and filter Routes
Route::get('/search', 'ProductsController@search')->name('products.search');

Route::middleware('auth:api')->group(function () {

    Route::group([], function () {
        // Order Routes
        Route::group([], function () {
            Route::patch('/orders/cancel/{order}', 'OrderController@cancelOrder')->name('orders.cancel');
            Route::get('/orders/{order}', 'OrderController@getById')->name('orders.get_by_id');
            Route::post('/order/tracking', 'OrderController@searchByInvoiceId')->name('order.tracking');
            Route::patch('/orders/cancel-request-by-driver/{order}', 'OrderController@cancelRequest')->name('orders.cancel_request');
            Route::patch('/orders/{order}/mark-as-paid', 'OrderController@paid')->name('orders.mark_as.paid');
            Route::post('/products/order', 'OrderController@order')->name('products.order');
            Route::patch('/orders/{order}/mark-as-in-shipping', 'OrderController@inShipping')->name('orders.mark_as.in_shipping');
            Route::patch('/orders/{order}/mark-as-not-in-shipping', 'OrderController@notInShipping')->name('orders.mark_as.not_in_shipping');
            Route::patch('/orders/{order}/mark-as-delivered', 'OrderController@delivered')->name('orders.mark_as.delivered');
            Route::patch('/orders/{order}/mark-as-not-delivered', 'OrderController@notDelivered')->name('orders.mark_as.not_delivered');
            Route::patch('/orders/{order}/mark-as-deleted', 'OrderController@deleteOrder')->name('orders.mark_as_deleted');
            Route::patch('/orders/{order}/accept-order-request', 'OrderController@acceptOrderRequest')->name('orders.mark_as.accept_order_request');
            Route::patch('/orders/{order}/change-status', 'OrderController@updateStatus')->name('orders.update_status');
        });

        // Cart Routes
        Route::group([], function () {

            Route::post('/products/{product_id}/cart', 'CartController@addToCart')->name('products.cart');
            Route::get('/cart/products/{product_id}/in-cart', 'CartController@getProduct')->name('cart.product_in_cart');
            Route::get('/cart', 'CartController@index')->name('cart.index');
            Route::get('/cart/total-details', 'CartController@getCart')->name('cart.get_cart');
            Route::delete('/cart/destroy/{cart_id}', 'CartController@destroy')->name('cart.destroy');
            Route::delete('/cart/{cart_id}/delete-addition/{addition_id}', 'CartController@deleteAddition')->name('cart.delete.addition');
            Route::patch('/cart/update/{cart}', 'CartController@update')->name('cart.update');
        });

        // Reviews Routes
        Route::group([], function () {
            Route::post('/products/{product}/submit-review', 'ReviewsController@submitReview')->name('products.reviews.submit');
            Route::get('/products/{product}/reviews', 'ReviewsController@listProductReview')->name('products.reviews');
        });

        // Favorite Products Routes
        Route::group([], function () {
            Route::get('/products/favorites', 'FavoritesController@listFavoriteProducts')->name('products.favorite');
            Route::post('/products/{product}/mark-as-favorite', 'FavoritesController@markAsFavorite')->name('products.favorite.mark');
            Route::delete('/products/{product}/mark-as-un-favorite', 'FavoritesController@markAsUnFavorite')->name('products.favorite.un_mark');
        });
    });
});


// products
Route::group([], function () {
    Route::get('/products/recommended', 'ProductsController@recommendedProducts')->name('products.recommended');
    Route::get('/products/offers', 'ProductsController@productOffers')->name('products.offers');
    Route::get('/products/top-selling/{store_id}', 'ProductsController@topSellingProduct')->name('products.top-selling');
    Route::get('/products/top-rated/{store_id}', 'ProductsController@topRatedProduct')->name('products.top-rated');
    Route::get('/products/{product}/media', 'ProductsController@showMedia')->name('products.show.media');
    Route::get('/products/{product}', 'ProductsController@show')->name('products.show');
    Route::get('/products/{categorization_id}/categorization', 'ProductsController@listByCategorization')->name('products.list_by_categorization');
    Route::get('/products', 'ProductsController@index')->name('products.index');
    Route::middleware('auth:api')->group(function () {

        Route::post('/products/store', 'ProductsController@store')->name('products.store');
        Route::patch('/products/update/{product}', 'ProductsController@update')->name('products.update');
        Route::delete('/products/delete/{product}', 'ProductsController@destroy')->name('products.delete');
        Route::delete('/products/{product}/media/{media}', 'ProductsController@deleteMedia')->name('products.delete_media');
    });
});


//Product's Groups
Route::middleware('auth:api')->group(function () {
    Route::patch('/products/addition-group/update/{addition_group_id}', 'ProductGroupAdditionsController@update')->name('products.addition_groups.update');
    Route::delete('/products/addition-group/delete/{addition_group_id}', 'ProductGroupAdditionsController@destroy')->name('products.addition_groups.delete');
});
//Product's Additions
Route::middleware('auth:api')->group(function () {
    Route::patch('/products/addition/update/{addition_group_id}', 'ProductAdditionsController@update')->name('products.addition.update');
    Route::patch('/products/addition/bulk-update', 'ProductAdditionsController@bulkUpdate')->name('products.addition.bulk_update');
    Route::delete('/products/addition/delete/{addition_group_id}', 'ProductAdditionsController@delete')->name('products.addition.delete');
    Route::delete('/products/{product}/addition/bulk-delete', 'ProductAdditionsController@bulkDelete')->name('products.addition.bulk_delete');
});


Route::middleware('auth:api')->group(function () {
    Route::get('/statistics', 'StatisticsController@statistics')->name('statistics.get');
});


// General Data Routes
Route::get('/general', 'GeneralDataController@index')->name('general.index');
Route::patch('/general/update/app-version', 'GeneralDataController@updateAppVersion')->name('general.update_app_version');
// Shipping Fee Routes
Route::get('/shipping-fee-per-distance/packages', 'ShippingFeePerDistancesController@index')->name('shipping_fee_per_distance.index');
// Contacts Routes
Route::post('/contacts', 'ContactsController@store')->name('contacts.store');



// Notifications Routes
Route::group([], function () {
    Route::post('notifications/send-via/sms', 'NotificationsController@sendViaSMS')->name('notifications.send_via.sms');
    Route::post('notifications/send-via/email', 'NotificationsController@sendViaEmail')->name('notifications.send_via.email');

    Route::get('notifications/get-unread-notifications-count', 'NotificationsController@unreadNotificationsAndMessagesCount')->middleware('auth:api')->name('notifications.unread_notifications_and_messages_count');

    Route::get('notifications/{notification}', 'NotificationsController@show')->middleware('auth:api')->name('notifications.show');
    Route::get('notifications', 'NotificationsController@index')->middleware('auth:api')->name('notifications.index');
});
