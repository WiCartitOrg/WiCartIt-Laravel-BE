<?php

namespace App\Validators\Buyer;

trait BuyerPaymentExecuteRequestRules {

    protected function makePaymentWithNewCardRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_buyer_id' => 'required | string | exists:buyers', 
            'unique_cart_id' => 'required | string | exists:carts',
            'buyer_bank_card_type' => 'required | string', 
            'buyer_bank_card_number'=> 'required',
            'buyer_bank_card_cvv' => 'required',
            'buyer_bank_card_expiry_month' => 'required',
            'buyer_bank_card_expiry_year' => 'required'     
        ];

        return $rules;
    }

    protected function makePaymentWithSavedCardRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_buyer_id' => 'required | string | exists:buyers', 
            'unique_cart_id' => 'required | string | exists:carts',
            //'purchase_price' => 'required | exists:carts'
        ];

        return $rules;
    }

    protected function makePaymentWithNewBankRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_buyer_id' => 'required | string | exists:buyers', 
            'unique_cart_id' => 'required | string | exists:carts',
            //'purchase_price' => 'required | exists:carts'
        ];

        return $rules;
    }

    protected function makePaymentWithSavedBankRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_buyer_id' => 'required | string | exists:buyers', 
            'unique_cart_id' => 'required | string | exists:carts',
            //'purchase_price' => 'required | exists:carts'
        ];

        return $rules;
    }
}
