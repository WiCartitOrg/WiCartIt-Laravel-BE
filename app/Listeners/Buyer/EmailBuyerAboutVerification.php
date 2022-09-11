<?php

namespace App\Listeners\Buyer;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

use App\Events\Buyer\BuyerHasRegistered;
use App\Mail\Buyer\SendRegisterVerificationMail; 

class EmailBuyerAboutVerification
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
     * @param  \App\Events\Buyer\BuyerRegistered  $event
     * @return void
     */
    public function handle(BuyerHasRegistered $event)
    {
        $buyer_request = $event->request;
        $buyer_mail = $event->request->buyer_email;
        $verify_link = $event->verify_link;
        Mail::to($buyer_mail)->send(new SendRegisterVerificationMail($buyer_request, $verify_link));
    }
}
