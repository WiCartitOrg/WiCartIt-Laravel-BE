<?php

namespace App\Services\Traits\AuthAbstractions\Admin;

use Illuminate\Http\Request;

use App\Services\Traits\ModelAbstractions\Admin\AdminAccessAbstraction;
use App\Services\Traits\Utilities\GenerateLinksService;


trait PasswordResetLinkAbstraction
{
    use AdminAccessAbstraction;
    use GenerateLinksService;
    /**
     * Send a new email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function SendPasswordResetMail(Request $request, string $url_title, array $other_url_params)/*:
        bool*/
    {
        //else: form verification url:
        $pass_reset_link = $this->GeneratePassResetLink($url_title, $other_url_params);
        //use event to create and send mailing instance:
        //event(new PassResetLinkWasFormed($request, $pass_reset_link));

        return $pass_reset_link;//true;
    }
}
