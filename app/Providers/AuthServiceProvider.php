<?php

namespace MixCode\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
 use MixCode\Order;
use MixCode\Policies\AdditionPolicy;
use MixCode\Policies\OrderPolicy;
use MixCode\Product;
use MixCode\Policies\FeaturePolicy;
use MixCode\Policies\LanguagePolicy;
use MixCode\Policies\ProductPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
      
        Product::class         => ProductPolicy::class,
        Order::class      => OrderPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Expires Tokens After 2 Weeks Only

        // Get Or Set When Tokens Are Expires
        Passport::tokensExpireIn(now()->addWeeks(2));

        // Get Or Set When Refreshed Tokens Are Expires
        Passport::refreshTokensExpireIn(now()->addMonth());
    }
}
