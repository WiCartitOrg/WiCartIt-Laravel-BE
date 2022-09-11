<?php

namespace App\Validators\Buyer;

trait BuyerPaymentDetailsRequestRules 
{
    protected function uploadCardDetailsRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_buyer_id' => 'required | string | size:10 | exists:buyers',
            'buyer_bank_card_type' => 'required | string', 
            'buyer_bank_card_number'=> 'required',
            'buyer_bank_card_cvv' => 'required',
            'buyer_bank_card_expiry_month' => 'required',
            'buyer_bank_card_expiry_year' => 'required'                                                                                           
        ];

        return $rules;
    }

    protected function fetchEachCardDetailsRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_buyer_id' => 'required | string | size:10 | exists:buyers', 
        ];

        return $rules;
    }

    protected function uploadAccountDetailsRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_buyer_id'=> 'required | string | size:10 | exists:buyers',
            'bank_account_first_name' => 'required | string ',
            'bank_account_middle_name' => 'required | string ',
            'bank_account_last_name' => 'required | string',
            'bank_account_type' => 'required | string',
            'bank_account_number' => 'required | string',
            'bank_name' => 'required | string',
        ];

        return $rules;
    }

    protected function fetchEachAccountDetailsRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_buyer_id'=> 'required | string | exists:buyers',
        ];

        return $rules;
    }

}
