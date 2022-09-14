<?php

namespace App\Validators\Buyer;

trait BuyerWishlistEditRequestRules 
{

//A wishlist be associated with many goods...
	protected function addProductsToWishlistRules(): array
	{
		//set validation rules:
        $rules = [
            'unique_buyer_id' => 'required | string | size:10 | exists:buyers',
            'wishlist_products_count'=> 'required | numeric',
            'wishlist_payment_currency' => 'required | string', //Either 'NGN', 'USD', 'EUR'
            'wishlist_products_cost' => 'required | string',
            'wishlist_shipping_cost' => 'required | string',
            'wishlist_total_cost' => 'required | string',

            'wishlist_attached_products_ids_quantities' => 'required | array',//|json,//all product ids added to this wishlist
            //'wishlist_payment_status' => 'required | string', //payment 'cleared' or ''
        ];

        return $rules;   
    }

    protected function editProductsOnWishlistRules(): array
    {
        $rules = [
            'unique_buyer_id' => 'required | string | size:10 | exists:buyers',
		    'unique_wishlist_id' => 'required | string | size:15 | exists:wishlists',
            'wishlist_products_count'=> 'required | numeric',
            'wishlist_payment_currency' => 'required | string', //Either 'NGN', 'USD', 'EUR'
            'wishlist_products_cost' => 'required | string',
            'wishlist_shipping_cost' => 'required | string',
            'wishlist_total_cost' => 'required | string',

            'wishlist_attached_products_ids_quantities' => 'required | array',//|json,//all product ids and quantities added to this wishlist
            //'wishlist_payment_status' => 'required | string', //payment 'cleared' or '' //either not paid for yet (is_cleared=false) or has been paid for - cleared (is_cleared=true); defaults to false
        ];

        return $rules;
    }


    protected function deleteWishlistRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_buyer_id' => 'required | string | size:10 | exists: buyers',
            'unique_wishlist_id' => 'required | string | size:15 | exists: wishlists',
        ];

        return $rules;
    }

    protected function convertWishlistToCartRules(): array
    {
         //set validation rules:
         $rules = [
            'unique_buyer_id' => 'required | string | size:10 | exists: buyers',
            'unique_wishlist_id' => 'required | string | size:15 | exists: wishlists',
        ];

        return $rules;
    }
}

?>