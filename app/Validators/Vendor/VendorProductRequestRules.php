<?php

namespace App\Validators\Vendor;

trait VendorProductRequestRules 
{
    protected function uploadProductTextDetailsRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_vendor_id'=> 'required | string | size:10 | exists:vendors',
            'product_category'=> 'required | string',
            'product_title'=> 'required | string',
            'product_summary'=> 'required | string',
            'product_description'=> 'required | string',
            'product_currency_of_payment'=> 'required | string',
            'product_price'=> 'required',
            'product_shipping_cost' =>'required',
            'product_add_info'=> 'required | string',
            'product_ship_guarantee_info'=> 'required | string'
        ];

        return $rules;
    }

    protected function uploadProductImageDetailsRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_vendor_id' => 'required | string | size:10 | exists:vendors',
            'unique_product_id' =>  'required | string | size:10 | exists:products',
            'main_image_1' => 'required | file', //at least one main pic image
            'main_image_2' => 'file',
            'logo_1' => 'required | file', //at least one logo image
            'logo2' => 'file'
        ];

        return $rules;
    }

    protected function fetchAllProductSummaryRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_vendor_id'=> 'required | string | size:10 | exists:vendors'
        ];

        return $rules;
    }

    protected function fetchEachProductDetailsRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_vendor_id'=> 'required | string | size:10 | exists:vendors',
            'unique_product_id' => 'required | string | size:10 | exists:products',
        ];

        return $rules;
    }


    protected function deleteEachProductDetailsRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_vendor_id'=> 'required | string | size:10 | exists:vendors',
            'unique_product_id' => 'required | string | size:10 | exists:products',
        ];

        return $rules;
    }

}

?>