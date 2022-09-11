<?php

namespace App\Validators\Vendor;

trait AdminExtrasRequestRules {


    protected function updateReferralDetailsRules(): array
    {
		//set validation rules:
        $rules = [
            'token_id' => 'required | exists:admins',
            'is_referral_prog_activated' => 'required',
            'referral_bonus_currency' => 'required',
            'referral_bonus' => 'required',
        ];
        return $rules;
    }


    protected function fetchReferralDetailsRules(): array
    {
        //set validation rules:
        $rules = [

            'token_id' => 'required | exists:admins',
        ];

        return $rules;
    }


    protected function disableReferralRules(): array
    {
        //set validation rules:
        $rules = [
            'token_id' => 'required | exists:admins',
        ];

        return $rules;
    }

    protected function fetchGeneralStatisticsRules(): array
    {
        //set validation rules:
        $rules = [
            'token_id' => 'required | string | exists:admins',
        ];

        return $rules;
    }

}