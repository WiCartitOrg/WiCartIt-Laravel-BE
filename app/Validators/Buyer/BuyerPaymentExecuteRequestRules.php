<?php

namespace App\Validators\Buyer;

trait BuyerPaymentExecuteRequestRules {

    protected function fetchMakePaymentRules(): array
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
