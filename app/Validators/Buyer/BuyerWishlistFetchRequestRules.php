<?php

namespace App\Validators\Buyer;

trait BuyerWishlistFetchRequestRules 
{
    protected function fetchAllWishlistsIDsOnlyRules(): array
    {
         //set validation rules:
         $rules = [
            'unique_buyer_id' => 'required | string | size:10 | exists:buyers', 
            //'wishlist_payment_status' => 'required | string', //pending or cleared
        ];
        return $rules; 
    }


    protected function fetchEachWishlistDetailByIDRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_buyer_id' => 'required | string | size:10 | exists:buyers', 
            'unique_wishlist_id' => 'required | string | size:15 | exists:wishlists',
        ];
        return $rules; 
    }

    protected function fetchAllWishlistsDetailsRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_buyer_id' => 'required | string | size:10 | exists:buyers', 
        ];
        return $rules; 
    }


    protected function fetchWishlistProductsIDsOnlyRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_buyer_id' => 'required | string | size:10 | exists:buyers', 
            'unique_wishlist_id' => 'required | string | size:15 | exists:wishlists',
        ];

        return $rules;   
    }

}
