<?php

namespace App\Mail\Buyer;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;

class SendRegisterVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $buyer_request;
    public $verify_link;

    public function __construct(Request $buyer_request, string $verify_link)
    {
        //init:
        $this->buyer_request = $buyer_request;
        $this->verify_link = $verify_link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Verification Mail for {$this->buyer_request->buyer_first_name} {$this->buyer_request->buyer_last_name} ")
                    ->view('buyer.verification-request');
    }
}
