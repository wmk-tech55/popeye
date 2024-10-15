<?php

namespace MixCode\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use MixCode\Category;
use MixCode\Country;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        \Schema::defaultStringLength(191);

        view()->composer(['layouts.includes._header', 'layouts.includes._footer'], function ($view) { 
            
            $categories = cache()->rememberForever('categories', function () {
                return Category::isParent()
                    ->has('children.products')
                    ->with(['children' => function ($builder) {
                        return $builder->has('products');
                    }])
                    ->active()
                    ->take(7)
                    ->get();
            });

            $view->with('categories', $categories);
        });


        view()->composer('dashboard.layouts.includes._fetch_all_countries', function ($view) {
            
            $countries = cache()->rememberForever('countries', function () {
                return Country::active()->get();
            });

            $view->with('countries', $countries);
        });


    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::if('admin', function ($type = 'admin') {
            return auth()->user()->isAdmin();
        });
        
       
        
        
        Blade::if('customer', function ($type = 'customer') {            
            return auth()->user()->isCustomer();
        });
        
        Blade::if('notCustomer', function ($type = null) {
            $user = auth()->user();
            return   $user->isAdmin() ;
        });
    }
}
