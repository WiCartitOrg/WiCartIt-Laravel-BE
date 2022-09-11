<?php

namespace App\Validators\Vendor;

trait VendorBusinessAndAccountRequestRules 
{
    protected function uploadBankAccountDetailsRules(): array
    {
        $rules = [
            'unique_vendor_id'=> 'required | string | size:10 | exists:vendors',
            'bank_account_first_name' => 'required | string ',
            'bank_account_middle_name' => 'required | string ',
            'bank_account_last_name' => 'required | string',
            'bank_account_type' => 'required | string',
            'bank_account_number' => 'required | string',   
            'bank_name' => 'required | string',
        ];

        return $rules;
    }


    protected function fetchEachBankAccountDetailsRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_vendor_id'=> 'required | string | size:10 | exists:vendors',
        ];

        return $rules;
    }


    protected function uploadBusinessDetailsRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_vendor_id'=> 'required | string | size:10 | exists:vendors',
            'business_name'=> 'required | string',
            'company_name'=> 'required | string',
            'business_country'=> 'required | string',
            'business_state'=> 'required | string',
            'business_city_or_town'=> 'required | string',
            'business_street_or_close'=> 'required',
            'business_apartment_suite_or_unit' =>'required',
            'business_phone_number'=> 'required | string',
            'business_email' => 'required | string'
        ];

        return $rules;
    }

    protected function fetchEachBusinessDetailsRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_vendor_id'=> 'required | string | size:10 | exists:vendors'
        ];

        return $rules;
    }

}