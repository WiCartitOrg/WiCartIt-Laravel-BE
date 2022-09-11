<?php

namespace App\Validators\Admin;

trait AdminCartBuyerRequestRules 
{
    protected function fetchAllCartBuyerIDsRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_vendor_id' => 'required | string | size:10 | exists:vendors', 
            'unique_cart_buyer' => 'required | string | size:15 | exists:carts'
        ];

        return $rules;
    }

    protected function fetchEachBuyerDetailsRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_vendor_id' => 'required | string | size:10 | exists:vendors',
            'unique_buyer_id' => 'required | string | size:10 | exists:buyers'
        ];

        return $rules;
    }


    /*protected function remindPendingBuyerRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_vendor_id' => 'required | exists:vendors',
            'buyer_email' => 'required | exists:buyers',
        ];

        return $rules;  
    }*/
}