<?php

namespace App\Validators\Vendor;

trait VendorCartFetchRequestRules 
{
    protected function fetchRelatedCartProductsDetailsRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_vendor_id' => 'required | string | size:10 | exists: vendors', 
            'cart_payment_status' => 'required | string',//pending or cleared
        ];
        return $rules;   
    }

    protected function fetchBuyersCustomerDetailsRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_vendor_id' => 'required | string | size:10 | exists: vendors', 
        ];
        return $rules;   
    }

}
