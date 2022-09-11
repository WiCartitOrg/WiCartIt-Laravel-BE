<?php

namespace App\Mail\Vendor;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;

class SendWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $vendor_request;

    public function __construct(Request $vendor_request)
    {
        //init:
        $this->vendor_request = $vendor_request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Welcome Mail for {$this->vendor_request->vendor_first_name} {$this->vendor_request->vendor_last_name} ")
                    ->view('vendor.welcome-greeting');
    }
}
