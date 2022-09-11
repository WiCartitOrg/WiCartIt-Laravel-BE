<?php

namespace App\Mail\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;

use App\Services\Traits\ModelAbstractions\Admin\AdminAccessAbstraction;

class SendPasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;
    use AdminAccessAbstraction;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $admin_request;
    public $pass_reset_link;
    public $adminModel;

    public function __construct(Request $admin_request, string $pass_reset_link)
    {
        //init:
        $this->admin_request = $admin_request;
        $this->pass_reset_link = $pass_reset_link;

        // Use this admin request object to get the names of the admin:
        $queryKeysValues = ['admin_email' => $admin_request->admin_email];
        $this->adminModel = $this?->AdminReadSpecificService($queryKeysValues);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Password Reset Mail for {$this->adminModel->admin_first_name} {$this->adminModel->admin_last_name}")
                    ->view('admin.password-reset');
    }
}
