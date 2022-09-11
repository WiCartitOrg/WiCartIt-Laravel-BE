<?php

namespace App\Validators\Buyer;

trait BuyerProductRequestRules 
{

    protected function fetchAllProductsRules(): array
    {
        //set validation rules:
        $rules = [
            //'unique_product_id' => 'required | unique:products | size:20',
        ];

        return $rules;
    }

    protected function fetchProductsByCategoryRules(): array
    {
        //set validation rules:
        $rules = [
            //'unique_product_id' => 'required | unique:products | size:20',
        ];

        return $rules;
    }

    //for logged in users:
    protected function fetchAllProductsSummaryRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_buyer_id' => 'required | string | size:10 | exists:buyers',
        ];

        return $rules;
    }

    //for logged in 
    protected function fetchSpecificProductWithVendorDetailsRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_buyer_id' =>'required | string | size:10 | exists:buyers',
            'unique_product_id' => 'required | string | size:10 | exists:products',
        ];

        return $rules;
    }



}

?>