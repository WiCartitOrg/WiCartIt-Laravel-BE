<?php

namespace App\Listeners\Vendor;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

use App\Events\Vendor\VendorHasRegistered;
use App\Mail\Vendor\SendRegisterVerificationMail; 

class EmailVendorAboutVerification
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
        $vendor_request = $event->request;
        $vendor_mail = $event->request->vendor_email;
        $verify_link = $event->verify_link;
        Mail::to($vendor_mail)->send(new SendRegisterVerificationMail($vendor_request, $verify_link));
    }
}
