<?php

namespace App\Listeners\Vendor;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

use App\Events\Vendor\PassResetLinkWasFormed;
use App\Mail\Vendor\SendPasswordResetMail; 


class EmailVendorAboutReset
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
     * @param  \App\Events\Vendor\PassResetLinkSent  $event
     * @return void
     */
    public function handle(PassResetLinkWasFormed $event)
    {
        $vendor_request = $event->request;
        $vendor_mail = $event->request->vendor_email;
        $pass_reset_link = $event->pass_reset_link;

        //Invoke mail object:
        Mail::to($vendor_mail)->send(new SendPasswordResetMail($vendor_request, $pass_reset_link));
    }
}
