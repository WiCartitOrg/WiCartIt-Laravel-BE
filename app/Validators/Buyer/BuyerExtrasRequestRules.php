<?php

namespace App\Validators\Buyer;

trait BuyerExtrasRequestRules 
{
    protected function trackGoodsRules(): array
    {
		//set validation rules:
        $rules = [
            'buyer_id' => 'required | unique:buyers',
            'cart_id' => 'required | unique:goods',
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



    protected function confirmDeliveryRules(): array
    {
        //set validation rules:
        $rules = [
            'buyer_id' => 'required | unique:buyers',
            'is_goods_delivered' => 'required | bool',
        ];

        return $rules;
    }


    protected function commentRateRules(): array
    {
        //set validation rules:
        $rules = [
            'buyer_id' => 'required | unique:buyers',
            'comments' => 'nullable | string',
            'rate' => 'nullable | numeric'
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
