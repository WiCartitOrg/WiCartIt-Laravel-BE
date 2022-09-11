<?php

namespace App\Validators\Buyer;

trait BuyerBillingAndShippingRequestRules 
{

    protected function uploadBillingDetailsRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_buyer_id'=> 'required | string | size:10 | exists:buyers',
            'billing_username' => 'required | string ',
            'billing_user_company' => 'string', 
            'billing_country' => 'string', 
            'billing_state'  => 'required | string',
            'billing_city_or_town'  => 'required | string',
            'billing_street_or_close'  => 'required | string',
            'billing_home_apartment_suite_or_unit'  => 'required | string',
            'billing_phone_number'  => 'required | string',
            'billing_email'  => 'required | string',
        ];

        return $rules;
    }


    protected function fetchBillingDetailsRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_buyer_id'=> 'required | string | size:10 | exists:buyers',
        ];

        return $rules;
    }

    protected function uploadShippingDetailsRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_buyer_id'=> 'required | string | size:10 | exists:buyers',
            'shipping_username' => 'required | string ',
            'shipping_user_company' => 'string', 
            'shipping_country' => 'required | string', 
            'shipping_state'  => 'required | string',
            'shipping_city_or_town'  => 'required | string',
            'shipping_street_or_close'  => 'required | string',
            'shipping_home_apartment_suite_or_unit'  => 'required | string',
            'shipping_phone_number'  => 'required | string',
            'shipping_email'  => 'required | string',
        ];

        return $rules;
    }

    protected function fetchShippingDetailsRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_buyer_id'=> 'required | string | size:10 | exists:buyers'
        ];

        return $rules;
    }
}