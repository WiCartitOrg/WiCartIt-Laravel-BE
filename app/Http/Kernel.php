<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\General\TrustProxies::class,
        \App\Http\Middleware\General\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\General\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\General\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\General\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            //'throttle:api',//remember to put this back in deployment...
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [

        //For Buyers:
        'BuyerEnsureLogoutState' => \App\Http\Middleware\Buyer\BuyerEnsureLogoutState::class,
        'BuyerCleanNullRecords' =>  \App\Http\Middleware\Buyer\BuyerDeleteAllNull::class,
        'BuyerConfirmLoginState' => \App\Http\Middleware\Buyer\BuyerConfirmLoginState::class,
        'BuyerConfirmVerifyState' => \App\Http\Middleware\Buyer\BuyerConfirmVerifyState::class,
        'DestroyTokenAfterLogout' => \App\Http\Middleware\Buyer\DestroyTokenAfterLogout::class,
        //For Carts
        'DeleteEmptyCarts' => \App\Http\Middleware\General\DeleteEmptyCarts::class,
        'CartEnsureNotCleared' => \App\Http\Middleware\General\CartEnsureNotCleared::class,
        //For Wishlists
        'DeleteEmptyWishlists' => \App\Http\Middleware\General\DeleteEmptyWishlists::class,
        //For Billing And Shipping:
        'DeleteEmptyBillingAndShipping' => \App\Http\Middleware\Buyer\DeleteEmptyBillingAndShipping::class,


        //For Vendors:
        'VendorEnsureLogoutState' => \App\Http\Middleware\Vendor\VendorEnsureLogoutState::class,
        'VendorCleanNullRecords' =>  \App\Http\Middleware\Vendor\VendorDeleteAllNull::class,
        'VendorConfirmLoginState' => \App\Http\Middleware\Vendor\VendorConfirmLoginState::class,
        'VendorConfirmVerifyState' => \App\Http\Middleware\Vendor\VendorConfirmVerifyState::class,
        'DestroyTokenAfterLogout' => \App\Http\Middleware\Vendor\DestroyTokenAfterLogout::class,

        //For Admin:
        'AdminCreateBoss' => \App\Http\Middleware\Admin\AdminCreateBoss::class,
        'AdminEnsureLogoutState' => \App\Http\Middleware\Admin\AdminEnsureLogoutState::class,
        'AdminCleanNullRecords' =>  \App\Http\Middleware\Admin\AdminDeleteAllNull::class,
        'AdminConfirmLoginState' => \App\Http\Middleware\Admin\AdminConfirmLoginState::class,
        'AdminConfirmVerifyState' => \App\Http\Middleware\Admin\AdminConfirmVerifyState::class,
        'DestroyTokenAfterLogout' => \App\Http\Middleware\Admin\DestroyTokenAfterLogout::class,

        //Check for sanctum token abilities:
        'abilities' => \Laravel\Sanctum\Http\Middleware\CheckAbilities::class,
        'ability' => \Laravel\Sanctum\Http\Middleware\CheckForAnyAbility::class,

        'auth' => \App\Http\Middleware\General\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\General\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \App\Http\Middleware\General\EnsureEmailIsVerified::class,

    ];
}
