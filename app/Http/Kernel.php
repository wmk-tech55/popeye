<?php

namespace MixCode\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \MixCode\Http\Middleware\TrustProxies::class,
        \MixCode\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \MixCode\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \RenatoMarinho\LaravelPageSpeed\Middleware\ElideAttributes::class,
        \RenatoMarinho\LaravelPageSpeed\Middleware\RemoveComments::class,
        \RenatoMarinho\LaravelPageSpeed\Middleware\CollapseWhitespace::class,
        \Fruitcake\Cors\HandleCors::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \MixCode\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \MixCode\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \MixCode\Http\Middleware\AddCreatorIdToRequest::class,
            \MixCode\Http\Middleware\AddCountryIdToRequest::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings', 
            \MixCode\Http\Middleware\AddCreatorIdToRequest::class 
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \MixCode\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'dashboard' => \MixCode\Http\Middleware\Dashboard::class,
        'admin' => \MixCode\Http\Middleware\Admin::class,
        'customer' => \MixCode\Http\Middleware\Customer::class,
        'not_customer' => \MixCode\Http\Middleware\NotCustomer::class,
        'pending' => \MixCode\Http\Middleware\Pending::class,
        'active' => \MixCode\Http\Middleware\Active::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \MixCode\Http\Middleware\RedirectIfAuthenticated::class,
        'language' => \MixCode\Http\Middleware\Language::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'checkout' => \MixCode\Http\Middleware\Checkout::class,
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces non-global middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \MixCode\Http\Middleware\Authenticate::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];
}
