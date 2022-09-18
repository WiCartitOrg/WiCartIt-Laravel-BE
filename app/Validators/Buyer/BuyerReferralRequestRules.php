<?php

namespace App\Validators\Buyer;

trait BuyerReferralRequestRules 
{
    protected function genUniqueReferralLinkRules(): array
    {
		//set validation rules:
        $rules = [
            'unique_buyer_id' => 'required | string | size:10 | exists:buyers',
        ]; 
        return $rules;
    }

    //admin can change the referral bonus to any amount per click...
    protected function getReferralBonusRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_buyer_id' => 'required | string | size:10 | exists:buyers',
        ];

        return $rules;
    }

    protected function followReferralLinkRules(): array
    {
        //set validation rules:
        $rules = [
            
        ];

        return $rules;
    }
}