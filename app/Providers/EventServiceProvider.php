<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        //bind app events to listeners:

        //Register:
        \App\Events\Buyer\BuyerHasRegistered::class => [
            \App\Listeners\Buyer\EmailBuyerAboutVerification::class,
            \App\Listeners\Buyer\EmailBuyerAboutWelcome::class,
            //others here...
        ],

        \App\Events\Vendor\VendorHasRegistered::class => [
            \App\Listeners\Vendor\EmailVendorAboutVerification::class,
            \App\Listeners\Vendor\EmailVendorAboutWelcome::class,
            //others here...
        ],


        //Password Reset:
        \App\Events\Buyer\PassResetLinkWasFormed::class => [
            \App\Listeners\Buyer\EmailBuyerAboutReset::class,
        ],

        \App\Events\Vendor\PassResetLinkWasFormed::class => [
            \App\Listeners\Vendor\EmailVendorAboutReset::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
