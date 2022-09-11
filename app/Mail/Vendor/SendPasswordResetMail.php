<?php

namespace App\Mail\Vendor;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;

use App\Services\Traits\ModelAbstractions\Vendor\VendorAccessAbstraction;

class SendPasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;
    use VendorAccessAbstraction;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $vendor_request;
    public $pass_reset_link;
    public $vendorModel;

    public function __construct(Request $vendor_request, string $pass_reset_link)
    {
        //init:
        $this->vendor_request = $vendor_request;
        $this->pass_reset_link = $pass_reset_link;

        // Use this vendor request object to get the names of the vendor:
        $queryKeysValues = ['vendor_email' => $vendor_request->vendor_email];
        $this->vendorModel = $this?->VendorReadSpecificService($queryKeysValues);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Password Reset Mail for {$this->vendorModel->vendor_first_name} {$this->vendorModel->vendor_last_name}")
                    ->view('vendor.password-reset');
    }
}
