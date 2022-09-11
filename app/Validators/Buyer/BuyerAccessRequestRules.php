<?php

namespace App\Validators\Buyer;

use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Password;

trait BuyerAccessRequestRules {

	protected function registerRules(): array
    {
		//set validation rules:
        $rules = [
            'buyer_first_name' => 'required | string', 
            'buyer_middle_name' => 'string',
            'buyer_last_name' => 'required | string',
            'buyer_phone_number' => 'required | string',
            'buyer_email' => 'required | string | email ',
            'buyer_password' => 'required | string | min:5 | confirmed',
            //'buyer_password_confirmation' => 'required | string | min:5 | '
        ];

        return $rules;
    }


    protected function loginRules(): array
    {
		//set validation rules:
        $rules = [
            'buyer_email' => 'required | string | email | different:buyer_password | exists:buyers',
            'buyer_password' => 'required | string | min:5 | different:buyer_email'
        ];

        return $rules;

    }


    /*protected function confirmLoginStateRules(): array
    {
        $rules =  [
            'unique_buyer_id'=>'required | exists:buyers',
        ];
        return $rules;
    }*/


    protected function changePasswordRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_buyer_id' => 'required | string | size:10 | different:new_password | exists:buyers',
            'new_password' => 'required | string | different:unique_buyer_id | confirmed',
        ];

        return $rules;
    }


    protected function sendRequestPassLinkRules(): array
    {
        //buyer is only a guest here, so no id is required:
        $rules = [
            'buyer_email' => 'required | string | email | exists:buyers',
        ];
        return $rules;
    }


    protected function implementResetPasswordRules(): array
    {
        $rules = [
            'unique_buyer_id' => 'required | string | size:10 | different:new_password | exists:buyers',
            'new_password' => 'required | string | different:unique_buyer_id | confirmed',
        ];
        return $rules;
    }


    protected function logoutRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_buyer_id' => 'required | string | size:10 | exists:buyers',
        ];

        return $rules;
    }

}

?>