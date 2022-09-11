<?php

namespace App\Listeners\Admin;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

use App\Events\Admin\PassResetLinkWasFormed;
use App\Mail\Admin\SendPasswordResetMail; 


class EmailAdminAboutReset
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
     * @param  \App\Events\Admin\PassResetLinkSent  $event
     * @return void
     */
    public function handle(PassResetLinkWasFormed $event)
    {
        $admin_request = $event->request;
        $admin_mail = $event->request->admin_email;
        $pass_reset_link = $event->pass_reset_link;

        //Invoke mail object:
        Mail::to($admin_mail)->send(new SendPasswordResetMail($admin_request, $pass_reset_link));
    }
}
