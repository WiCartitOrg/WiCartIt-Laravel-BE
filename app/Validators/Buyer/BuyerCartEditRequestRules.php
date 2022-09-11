<?php

namespace App\Validators\Buyer;

trait BuyerCartEditRequestRules 
{

//A cart be associated with many goods...
	protected function addProductsToPendingCartRules(): array
	{
		//set validation rules:
        $rules = [
            'unique_buyer_id' => 'required | string | size:10 | exists:buyers',
            'cart_products_count'=> 'required | numeric',
            'cart_payment_currency' => 'required | string', //Either 'NGN', 'USD', 'EUR'
            'cart_products_cost' => 'required | string',
            'cart_shipping_cost' => 'required | string',
            'cart_total_cost' => 'required | string',

            'cart_attached_products_ids_quantities' => 'required | array',//|json,//all product ids added to this cart
            'cart_payment_status' => 'required | string', //payment 'cleared' or 'pending'
        ];

        return $rules;   
    }

    protected function editProductsOnPendingCartRules(): array
    {
        $rules = [
            'unique_buyer_id' => 'required | string | size:10 | exists:buyers',
		    'unique_cart_id' => 'required | string | size:15 | exists:carts',
            'cart_products_count'=> 'required | numeric',
            'cart_payment_currency' => 'required | string', //Either 'NGN', 'USD', 'EUR'
            'cart_products_cost' => 'required | string',
            'cart_shipping_cost' => 'required | string',
            'cart_total_cost' => 'required | string',

            'cart_attached_products_ids_quantities' => 'required | array',//|json,//all product ids and quantities added to this cart
            'cart_payment_status' => 'required | string', //payment 'cleared' or 'pending' //either not paid for yet (is_cleared=false) or has been paid for - cleared (is_cleared=true); defaults to false
        ];

        return $rules;
    }


    protected function deletePendingCartRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_buyer_id' => 'required | string | size:10 | exists: buyers',
            'unique_cart_id' => 'required | string | size:15 | exists: carts',
        ];

        return $rules;
    }
}

?>