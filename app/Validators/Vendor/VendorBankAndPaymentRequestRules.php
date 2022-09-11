<?php

namespace App\Validators\Vendor;

trait AdminBankAndPaymentRequestRules {

    protected function updateBankDetailsRules(): array
    {
        //set validation rules:
        $rules = [
            'token_id'=> 'required | string | size:10 | exists:admins',
            /*'bank_acc_first_name'=> 'required | string',
            'bank_acc_middle_name'=> 'string',
            'bank_acc_last_name'=> 'required | string',
            'bank_acc_email'=> 'required | string',
            'bank_acc_password'=> 'string',
            'bank_acc_phone_num'=> 'string',
            'country_of_opening' =>'required',
            'currency_of_operation'=> 'required | string',
            'bank_account_type' => 'required | string',
            'bank_account_number' => 'required | string',
            'bank_acc_additional_info' => 'string'*/
        ];

        return $rules;
    }

    protected function fetchBankDetailsRules(): array
    {
        //set validation rules:
        $rules = [
            //'token_id'=> 'required | string | size:10 | exists:admins'
        ];

        return $rules;
    }
}