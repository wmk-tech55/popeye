<?php
Route::get('/categories/get-by-categorization', 'CategoriesController@getByCategorization')->name('categories.get_by_categorization');

Route::get('/', 'DashboardController@index')->name('dashboard');

// Get By Country Route
Route::get('/cities/get-by-country', 'CitiesController@getByCountry')->name('cities.get_by_country');


Route::group([], function () {
    Route::get('/users/edit/profile', 'UsersController@editProfile')->name('users.edit_profile');
    Route::patch('/users/update/profile', 'UsersController@CompanyProfile')->name('users.company.update.profile');
});


Route:: patch('/companies/{user}/update-password', 'CompaniesController@updatePassword')->name('companies.update.password');
Route:: patch('/users/{user}/update-password', 'UsersController@updatePassword')->name('users.update.password');
// Admins Only Routes
Route::middleware('admin')->group(function () {
    // Settings Routes
    Route::group([], function () {
        Route::get('/delete-media', 'SettingsController@deleteMedia')->name('settings.media.destroy');
        Route::get('/settings', 'SettingsController@show')->name('settings.show');
        Route::post('/settings', 'SettingsController@store')->name('settings.store');
    });
    // Shipping Fee Routes
    Route::group([], function () {
        Route::post('/shipping-fee-per-distance', 'ShippingFeePerDistancesController@store')->name('shipping_fee_per_distance.store');
    });

    // Users Routes
    Route::group([], function () {
        Route::delete('/users/destroy-group', 'UsersController@destroyGroup')->name('users.destroyGroup');
        Route::get('/users/trashed', 'UsersController@trashed')->name('users.trashed');
        Route::patch('/users/{id}/restore', 'UsersController@restore')->name('users.restore');
        Route::delete('/users/{id}/force_delete', 'UsersController@forceDelete')->name('users.force_delete');
        Route:: get('/users/changeCountry/{country_id}', 'UsersController@changeCountry')->name('users.changeCountry');
        Route::delete('/users/force-destroy-group', 'UsersController@forceDestroyGroup')->name('users.forceDestroyGroup');
        Route::resource('/users', 'UsersController');
    });


    // drivers Routes
    Route::group([], function () {
        Route::get('/drivers/{driver}/mark-as-active', 'DriversController@active')
        ->name('drivers.mark_as.active')
        ->middleware('admin');
    Route::get('/drivers/{driver}/mark-as-pending', 'DriversController@pending')
        ->name('drivers.mark_as.pending')
        ->middleware('admin');
        Route::delete('/drivers/destroy-group', 'DriversController@destroyGroup')->name('drivers.destroyGroup');
        Route::get('/drivers/{product}/destroy-media', 'DriversController@destroyMedia')->name('drivers.media.destroy');
        Route::get('/drivers/trashed', 'DriversController@trashed')->name('drivers.trashed');
        Route::patch('/drivers/{id}/restore', 'DriversController@restore')->name('drivers.restore');
        Route::delete('/drivers/{id}/force_delete', 'DriversController@forceDelete')->name('drivers.force_delete');
        Route::patch('/drivers/{user}/update-password', 'DriversController@updatePassword')->name('drivers.update.password');
        Route::delete('/drivers/force-destroy-group', 'DriversController@forceDestroyGroup')->name('drivers.forceDestroyGroup');
        Route::resource('/drivers', 'DriversController');
    });

    // companies Routes
    Route::group([], function () {
        Route::get('/companies/{company}/mark-as-active', 'CompaniesController@active')
        ->name('companies.mark_as.active')
        ->middleware('admin');
    Route::get('/companies/{company}/mark-as-pending', 'CompaniesController@pending')
        ->name('companies.mark_as.pending')
        ->middleware('admin');
        Route::delete('/companies/destroy-group', 'CompaniesController@destroyGroup')->name('companies.destroyGroup');
        Route::get('/companies/trashed', 'CompaniesController@trashed')->name('companies.trashed');
        Route::patch('/companies/{id}/restore', 'CompaniesController@restore')->name('companies.restore');
        Route::delete('/companies/{id}/force_delete', 'CompaniesController@forceDelete')->name('companies.force_delete');
        Route::patch('/companies/{user}/update-password', 'CompaniesController@updatePassword')->name('companies.update.password');
        Route::delete('/companies/force-destroy-group', 'CompaniesController@forceDestroyGroup')->name('companies.forceDestroyGroup');
        Route::patch('/companies/{company}/work-times/{work_time}', 'CompaniesController@updateWorkTime')->name('companies.update.work_time');

        Route::resource('/companies', 'CompaniesController');
    });

    // wallets Routes
    Route::group([], function () {

        Route::get('/wallets/{company}', 'WalletsController@payTheDue')->name('wallets.pay_the_due');
        Route::resource('/wallets', 'WalletsController');
    });

    //order reports group 
    Route::group([], function () {

        Route::patch('/reports/{user_id}/pay-order-reports', 'OrderReportsController@update')->name('reports.pay_off_order_reports');
    });



    // Countries Routes
    Route::group([], function () {
        Route::delete('/countries/destroy-group', 'CountriesController@destroyGroup')->name('countries.destroyGroup');
        Route::get('/countries/trashed', 'CountriesController@trashed')->name('countries.trashed');
        Route::patch('/countries/{id}/restore', 'CountriesController@restore')->name('countries.restore');
        Route::delete('/countries/{id}/force_delete', 'CountriesController@forceDelete')->name('countries.force_delete');
        Route::delete('/countries/force-destroy-group', 'CountriesController@forceDestroyGroup')->name('countries.forceDestroyGroup');
        Route::resource('/countries', 'CountriesController');
    });

    // Cities Routes
    Route::group([], function () {

        // Multi Delete Route
        Route::delete('/cities/destroy-group', 'CitiesController@destroyGroup')->name('cities.destroyGroup');
        Route::get('/cities/trashed', 'CitiesController@trashed')->name('cities.trashed');
        Route::patch('/cities/{id}/restore', 'CitiesController@restore')->name('cities.restore');
        Route::delete('/cities/{id}/force_delete', 'CitiesController@forceDelete')->name('cities.force_delete');
        Route::delete('/cities/force-destroy-group', 'CitiesController@forceDestroyGroup')->name('cities.forceDestroyGroup');
        Route::resource('/cities', 'CitiesController');
    });



    // DiscountsController Routes
    Route::group([], function () {
        // Multi Delete Route
        Route::delete('/discounts/destroy-group', 'DiscountsController@destroyGroup')->name('discounts.destroyGroup');
        Route::get('/discounts/{discount}/mark-as-published', 'DiscountsController@publish')
            ->name('discounts.mark_as.published')
            ->middleware('admin');
        Route::get('/discounts/{discount}/mark-as-pending', 'DiscountsController@pending')
            ->name('discounts.mark_as.pending')
            ->middleware('admin');
        Route::get('/discounts/{discount}/destroy-media', 'DiscountsController@destroyMedia')->name('discounts.media.destroy');
        Route::get('/discounts/trashed', 'DiscountsController@trashed')->name('discounts.trashed');
        Route::patch('/discounts/{id}/restore', 'DiscountsController@restore')->name('discounts.restore');
        Route::delete('/discounts/{id}/force_delete', 'DiscountsController@forceDelete')->name('discounts.force_delete');
        Route::get('/discounts/category_id/', 'DiscountsController@getProductsByCategoryId')->name('products.get_products_by_category_id');
        Route::delete('/discounts/force-destroy-group', 'DiscountsController@forceDestroyGroup')->name('discounts.forceDestroyGroup');
        Route::resource('/discounts', 'DiscountsController');
    });


    // promoCodesController Routes
    Route::group([], function () {
        // Status Routes
        Route::get('/promoCodes/{promoCode}/mark-as-active', 'PromoCodesController@active')
            ->name('promoCodes.mark_as.active')
            ->middleware('admin');
        Route::get('/promoCodes/{promoCode}/mark-as-inActive', 'PromoCodesController@inActive')
            ->name('promoCodes.mark_as.inActive')
            ->middleware('admin');
        Route::delete('/promoCodes/destroy-group', 'PromoCodesController@destroyGroup')->name('promoCodes.destroyGroup');
        Route::get('/promoCodes/trashed', 'PromoCodesController@trashed')->name('promoCodes.trashed');
        Route::patch('/promoCodes/{id}/restore', 'PromoCodesController@restore')->name('promoCodes.restore');
        Route::delete('/promoCodes/{id}/force_delete', 'PromoCodesController@forceDelete')->name('promoCodes.force_delete');
        Route::delete('/promoCodes/force-destroy-group', 'PromoCodesController@forceDestroyGroup')->name('promoCodes.forceDestroyGroup');
        Route::resource('/promoCodes', 'PromoCodesController');
    });



    // Faqs Routes
    Route::group([], function () {
        // Status Routes
        Route::get('/faqs/{faq}/mark-as-active', 'FaqsController@active')
            ->name('faqs.mark_as.active');
        Route::get('/faqs/{faq}/mark-as-inactive', 'FaqsController@inActive')
            ->name('faqs.mark_as.in_active');
        Route::delete('/faqs/destroy-group', 'FaqsController@destroyGroup')->name('faqs.destroyGroup');
        Route::get('/faqs/trashed', 'FaqsController@trashed')->name('faqs.trashed');
        Route::patch('/faqs/{id}/restore', 'FaqsController@restore')->name('faqs.restore');
        Route::delete('/faqs/{id}/force_delete', 'FaqsController@forceDelete')->name('faqs.force_delete');
        Route::delete('/faqs/force-destroy-group', 'FaqsController@forceDestroyGroup')->name('faqs.forceDestroyGroup');
        Route::resource('/faqs', 'FaqsController');
    });


    // BannersController Routes
    Route::group([], function () {
        // Multi Delete Route
        Route::delete('/banners/destroy-group', 'BannersController@destroyGroup')->name('banners.destroyGroup');
        // Status Routes
        Route::get('/banners/{banner}/mark-as-published', 'BannersController@publish')
            ->name('banners.mark_as.published')
            ->middleware('admin');
        Route::get('/banners/{banner}/mark-as-pending', 'BannersController@pending')
            ->name('banners.mark_as.pending')
            ->middleware('admin');
        Route::get('/banners/{banner}/destroy-media', 'BannersController@destroyMedia')->name('banners.media.destroy');
        Route::get('/banners/trashed', 'BannersController@trashed')->name('banners.trashed');
        Route::patch('/banners/{id}/restore', 'BannersController@restore')->name('banners.restore');
        Route::delete('/banners/{id}/force_delete', 'BannersController@forceDelete')->name('banners.force_delete');
        Route::delete('/banners/force-destroy-group', 'BannersController@forceDestroyGroup')->name('banners.forceDestroyGroup');
        Route::resource('/banners', 'BannersController');
    });


    // Categories Routes
    Route::group([], function () {
        // Multi Delete Route
        Route::delete('/categories/destroy-group', 'CategoriesController@destroyGroup')->name('categories.destroyGroup');

        // trashed Routes
        Route::get('/categories/trashed', 'CategoriesController@trashed')->name('categories.trashed');
        Route::patch('/categories/{id}/restore', 'CategoriesController@restore')->name('categories.restore');
        Route::delete('/categories/{id}/force_delete', 'CategoriesController@forceDelete')->name('categories.force_delete');
        Route::get('/categories/subCategories/{parent_id}', 'CategoriesController@getCategoryByParentId')->name('categories.subCategories.index');
        Route::get('/categories/parent/', 'CategoriesController@getCategoriesByParentId')->name('categories.get_categories_by_parent_id');
        Route::get('/discounts/get-by-category', 'CategoriesController@getByCategory')->name('categories.get_by_category');
        Route::post('/categories/quick/store', 'CategoriesController@quickStore')->name('categories.quick.store');
        Route::delete('/categories/force-destroy-group', 'CategoriesController@forceDestroyGroup')->name('categories.forceDestroyGroup');
        Route::resource('/categories', 'CategoriesController');
    });




    // ClassificationsController Routes
    Route::group([], function () {
        // Multi Delete Route
        Route::delete('/classifications/destroy-group', 'ClassificationsController@destroyGroup')->name('classifications.destroyGroup');
        Route::get('/classifications/trashed', 'ClassificationsController@trashed')->name('classifications.trashed');
        Route::patch('/classifications/{id}/restore', 'ClassificationsController@restore')->name('classifications.restore');
        Route::delete('/classifications/{id}/force_delete', 'ClassificationsController@forceDelete')->name('classifications.force_delete');
        Route::get('/classifications/get-by-categorization', 'ClassificationsController@getByCategorization')->name('classifications.get_by_categorization');
        Route::delete('/classifications/force-destroy-group', 'ClassificationsController@forceDestroyGroup')->name('classifications.forceDestroyGroup');
        Route::resource('/classifications', 'ClassificationsController');
    });

    // Categorizations Routes
    Route::group([], function () {
        // Multi Delete Route
        Route::delete('/categorizations/destroy-group', 'CategorizationsController@destroyGroup')->name('categorizations.destroyGroup');
        Route::get('/categorizations/trashed', 'CategorizationsController@trashed')->name('categorizations.trashed');
        Route::patch('/categorizations/{id}/restore', 'CategorizationsController@restore')->name('categorizations.restore');
        Route::delete('/categorizations/{id}/force_delete', 'CategorizationsController@forceDelete')->name('categorizations.force_delete');
        Route::delete('/categorizations/force-destroy-group', 'CategorizationsController@forceDestroyGroup')->name('categorizations.forceDestroyGroup');
        Route::resource('/categorizations', 'CategorizationsController');
    });



    // Contacts Routes
    Route::group([], function () {
        Route::delete('/contacts/destroy-group', 'ContactsController@destroyGroup')->name('contacts.destroyGroup');
        Route::resource('/contacts', 'ContactsController');
    });
});




// Products Routes
Route::group([], function () {
    // Status Routes
    Route::get('/products/{product}/mark-as-published', 'ProductsController@publish')
        ->name('products.mark_as.published')
        ->middleware('admin');
    Route::get('/products/{product}/mark-as-pending', 'ProductsController@pending')
        ->name('products.mark_as.pending')
        ->middleware('admin');
    Route::get('/products/{product}/mark-as-active', 'ProductsController@active')
        ->name('products.mark_as.active');

    Route::get('/products/{product}/mark-as-inactive', 'ProductsController@inActive')
        ->name('products.mark_as.inactive');
    Route::get('/products/{product}/destroy-media', 'ProductsController@destroyMedia')->name('products.media.destroy');
    Route::delete('/products/destroy-group', 'ProductsController@destroyGroup')->name('products.destroyGroup');
    Route::get('/products/trashed', 'ProductsController@trashed')->name('products.trashed');
    Route::patch('/products/{id}/restore', 'ProductsController@restore')->name('products.restore');
    Route::delete('/products/{id}/force_delete', 'ProductsController@forceDelete')->name('products.force_delete');
    Route::get('/products/{id}/duplicate', 'ProductsController@duplicatePage')->name('products.duplicatePage');
    /*  Route::post('/products/{id}/new-product-variation', 'ProductsController@addNewProductVariation')->name('products.new_variation');
    Route::post('/products/{id}/new-product-additions', 'ProductsController@addNewProductAddition')->name('products.new_addition'); */
    Route::get('products/addition-group-row', 'ProductsController@additionGroupRow')->name('products.addition_group_row');
    Route::get('products/addition-row', 'ProductsController@additionRow')->name('products.addition_row');
    Route::delete('/products/force-destroy-group', 'ProductsController@forceDestroyGroup')->name('products.forceDestroyGroup');
    Route::resource('/products', 'ProductsController');
});

// additions 
Route::group([], function () {

    Route::patch('/additions/{addition}/update', 'ProductAdditionsController@update')->name('additions.update');
    Route::delete('/additions/{addition}/delete', 'ProductAdditionsController@destroy')->name('additions.destroy');
});
// Order Routes 
Route::group([], function () {

    Route::get('/orders/{order}/mark-as-paid', 'OrderController@paid')
        ->name('orders.mark_as.paid');
    Route::get('/orders/{order}/mark-as-not-paid', 'OrderController@notPaid')
        ->name('orders.mark_as.not_paid');
    Route::get('/orders/{order}/mark-as-completed', 'OrderController@completed')
        ->name('orders.mark_as.completed');
    Route::get('/orders/{order}/mark-as-not-completed', 'OrderController@notCompleted')
        ->name('orders.mark_as.not_completed');
    Route::patch('/orders/{order}/assign-driver', 'OrderController@assignDriver')
        ->name('orders.assign_driver');
    Route::get('/orders/companies', 'OrderController@companiesOrders')->name('orders.companies_orders');
    Route::delete('/orders/destroy-group', 'OrderController@destroyGroup')->name('orders.destroyGroup');
    Route::get('/orders/trashed', 'OrderController@trashed')->name('orders.trashed');
    Route::patch('/orders/{id}/restore', 'OrderController@restore')->name('orders.restore');
    Route::delete('/orders/{id}/force_delete', 'OrderController@forceDelete')->name('orders.force_delete');
    Route::get('/orders/{order}/print', 'OrderController@print')->name('orders.print');
    Route::delete('/orders/force-destroy-group', 'OrderController@forceDestroyGroup')->name('orders.forceDestroyGroup');
    Route::resource('/orders', 'OrderController')->only(['index', 'create', 'show', 'destroy', 'update']);
});

// Notifications Routes
Route::group([], function () {
    Route::get('/notifications/mark-all-as-read', 'NotificationsController@markAllAsRead')->name('notifications.mark_all_as_read');
    Route::get('/notifications/{notification}', 'NotificationsController@show')->name('notifications.show');
    Route::get('/notifications/{notification}/mark-as-read', 'NotificationsController@markAsRead')->name('notifications.mark_as_read');
    Route::get('/notifications/{notification}/mark-as-unread', 'NotificationsController@markAsUnRead')->name('notifications.mark_as_unread');
});
