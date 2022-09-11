<?php

namespace App\Listeners\Vendor;

use App\Events\Vendor\VendorHasRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

use App\Mail\Vendor\SendWelcomeMail;

class EmailVendorAboutWelcome
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Vendor\VendorRegistered  $event
     * @return void
     */
    public function handle(VendorHasRegistered $event)
    {
        $vendor_mail = $event->request->vendor_email;
        $vendor_request = $event->request;
        Mail::to($vendor_mail)->send(new SendWelcomeMail($vendor_request));
    }
}
