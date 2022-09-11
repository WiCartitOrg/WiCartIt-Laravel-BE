<?php

namespace App\Listeners\Buyer;

use App\Events\Buyer\BuyerHasRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

use App\Mail\Buyer\SendWelcomeMail;

class EmailBuyerAboutWelcome
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
        $buyer_mail = $event->request->buyer_email;
        $buyer_request = $event->request;
        Mail::to($buyer_mail)->send(new SendWelcomeMail($buyer_request));
    }
}
