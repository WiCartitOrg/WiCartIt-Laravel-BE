<?php

namespace App\Validators\Buyer;

trait BuyerReferralRequestRules 
{
    protected function genUniqueReferralLinkRules(): array
    {
		//set validation rules:
        $rules = [
            'unique_buyer_id' => 'required | string | exists:buyers',
        ];

        return $rules;
    }

    //admin can change the referral bonus to any amount per click...
    protected function referralBonusRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_buyer_id' => 'required | string | exists:buyers',
        ];

        return $rules;
    }

    protected function referralLinkUseRules(): array
    {
        //set validation rules:
        $rules = [
            
        ];

        return $rules;
    }
}