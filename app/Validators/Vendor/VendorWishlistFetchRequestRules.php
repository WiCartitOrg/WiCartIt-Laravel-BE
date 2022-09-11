<?php

namespace App\Validators\Vendor;

trait VendorWishlistFetchRequestRules 
{
    protected function fetchRelatedWishlistProductsDetailsRules(): array
    {
         //set validation rules:
         $rules = [
            'unique_vendor_id' => 'required | string | size:10 | exists:buyers', 
        ];
        return $rules; 
    }


    protected function fetchWishingBuyersDetailsRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_vendor_id' => 'required | string | size:10 | exists:vendors', 
        ];
        return $rules; 
    }

}
