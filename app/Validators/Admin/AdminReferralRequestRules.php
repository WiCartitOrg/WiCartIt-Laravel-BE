<?php

namespace App\Validators\Admin;

trait AdminReferralRequestRules
{

    protected function updateReferralDetailsRules(): array
    {
		//set validation rules:
        $rules = [
            'unique_admin_id' => 'required | string | size:10 | exists:admins',
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
            'unique_admin_id' => 'required | string | size:10 | exists:admins',
        ];
        return $rules;
    }


    protected function disableReferralRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_admin_id' => 'required | string | size:10 | exists:admins',
        ];
        return $rules;
    }
}