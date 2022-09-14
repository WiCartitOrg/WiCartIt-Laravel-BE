<?php

namespace App\Validators\Buyer;

trait BuyerExtrasRequestRules 
{
    protected function trackProductsLocationRules(): array
    {
		//set validation rules:
        $rules = [
            'unique_buyer_id' => 'required | string | size:10 | exists:buyers',
            'unique_cart_id' => 'required | string | size:15 | exists:carts',
        ];

        return $rules;
    }

    protected function confirmDeliveryRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_buyer_id' => 'required | string | size:10 | exists:buyers',
            'unique_cart_id' => 'required | string | size:15 | exists:carts',
            'is_products_delivered' => 'required | bool',
        ];

        return $rules;
    }

    protected function commentRateServiceRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_buyer_id' => 'required | string | size:10 | exists:buyers',
            'comments' => 'nullable | string',
            'rate' => 'nullable | numeric'
        ];

        return $rules;
    }


    protected function fetchGeneralStatisticsRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_buyer_id' => 'required | string |  exists:buyers',
        ];

        return $rules;
    }

    protected function viewOtherCommentsRates(): array
    {
        //set validation rules:
        $rules = [
            'buyer_id' => 'required | unique:buyers',
        ];

        return $rules;
    }

}
