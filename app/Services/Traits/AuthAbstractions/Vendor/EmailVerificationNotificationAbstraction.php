<?php

namespace App\Services\Traits\AuthAbstractions\Vendor;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Events\Dispatcher;

use App\Services\Traits\ModelAbstractions\Vendor\VendorAccessAbstraction;
use App\Services\Traits\Utilities\GenerateLinksService;

use App\Events\Vendor\VendorHasRegistered;


trait EmailVerificationNotificationAbstraction
{
    use VendorAccessAbstraction;
    use GenerateLinksService;
    /**
     * Send a new email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function SendVerificationReqMail(Request $request, string $url_title, array $other_url_params)/*:
        bool*/
    {
        //redirect if vendor is verified:
        $email_was_verified = $this->VendorDetailsFoundService($request)->is_email_verified;

        if($email_was_verified) 
        {
            //redirect()->intended(RouteServiceProvider::HOME);
            return true;
        }

        //else: form verification url:
        $verify_link = $this->GenerateRegisterVerLink($url_title, $other_url_params);
        //use event to create and send mailing instance:
        //event(new VendorHasRegistered($request, $verify_link));

        return  $verify_link;//true;
    }
}
