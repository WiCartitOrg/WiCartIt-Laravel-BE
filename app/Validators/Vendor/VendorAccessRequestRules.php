<?php

namespace App\Validators\Vendor;

use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Password;

trait VendorAccessRequestRules {

	protected function registerRules(): array
    {
		//set validation rules:
        $rules = [
            'vendor_first_name' => 'required | string', 
            'vendor_middle_name' => 'string',
            'vendor_last_name' => 'required | string',
            'vendor_phone_number' => 'required | string',
            'vendor_email' => 'required | string | email ',
            'vendor_password' => 'required | string | min:5 | confirmed',
            //'vendor_password_confirmation' => 'required | string | min:5 | '
        ];

        return $rules;
    }


    protected function loginRules(): array
    {
		//set validation rules:
        $rules = [
            'vendor_email' => 'required | string | email | different:vendor_password | exists:vendors',
            'vendor_password' => 'required | string | min:5 | different:vendor_email'
        ];

        return $rules;

    }


    /*protected function confirmLoginStateRules(): array
    {
        $rules =  [
            'unique_vendor_id'=>'required | exists:vendors',
        ];
        return $rules;
    }*/


    protected function changePasswordRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_vendor_id' => 'required | string| different:new_password | exists:vendors',
            'new_password' => 'required | string | different:unique_vendor_id | confirmed',
        ];

        return $rules;
    }


    protected function sendRequestPassLinkRules(): array
    {
        //vendor is only a guest here, so no id is required:
        $rules = [
            'vendor_email' => 'required | string | email | exists:vendors',
        ];
        return $rules;
    }


    protected function implementResetPasswordRules(): array
    {
        $rules = [
            'unique_vendor_id' => 'required | string | different:new_password | exists:vendors',
            'new_password' => 'required | string | different:unique_vendor_id | confirmed',
        ];
        return $rules;
    }


    protected function logoutRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_vendor_id' => 'required' //| unique: vendors',
        ];

        return $rules;
    }

}

?>