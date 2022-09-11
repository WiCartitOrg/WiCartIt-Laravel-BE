<?php

namespace App\Mail\Buyer;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;

use App\Services\Traits\ModelAbstractions\Buyer\BuyerAccessAbstraction;

class SendPasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;
    use BuyerAccessAbstraction;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $buyer_request;
    public $pass_reset_link;
    public $buyerModel;

    public function __construct(Request $buyer_request, string $pass_reset_link)
    {
        //init:
        $this->buyer_request = $buyer_request;
        $this->pass_reset_link = $pass_reset_link;

        // Use this buyer request object to get the names of the buyer:
        $queryKeysValues = ['buyer_email' => $buyer_request->buyer_email];
        $this->buyerModel = $this?->BuyerReadSpecificService($queryKeysValues);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Password Reset Mail for {$this->buyerModel->buyer_first_name} {$this->buyerModel->buyer_last_name}")
                    ->view('buyer.password-reset');
    }
}
