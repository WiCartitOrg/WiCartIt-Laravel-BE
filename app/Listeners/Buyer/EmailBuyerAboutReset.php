<?php

namespace App\Listeners\Buyer;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

use App\Events\Buyer\PassResetLinkWasFormed;
use App\Mail\Buyer\SendPasswordResetMail; 


class EmailBuyerAboutReset
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
     * @param  \App\Events\Buyer\PassResetLinkSent  $event
     * @return void
     */
    public function handle(PassResetLinkWasFormed $event)
    {
        $buyer_request = $event->request;
        $buyer_mail = $event->request->buyer_email;
        $pass_reset_link = $event->pass_reset_link;

        //Invoke mail object:
        Mail::to($buyer_mail)->send(new SendPasswordResetMail($buyer_request, $pass_reset_link));
    }
}
