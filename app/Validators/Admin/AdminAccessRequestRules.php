<?php


namespace App\Validators\Admin;

use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Password;

trait AdminAccessRequestRules {

    
    protected function registerRules(): array
    {
		//set validation rules:
        $rules = [
            'admin_first_name' => 'required | string | ', 
            'admin_middle_name' => 'string',
            'admin_last_name' => 'required | string',
            'admin_phone_number' => 'string',
            'admin_email' => 'required | string',
            'admin_password' => 'required | string | min:5'
        ];

        return $rules;
    }


    protected function loginRules(): array
    {
		//set validation rules:
        $rules = [
            'admin_email' => 'required | unique:admins | different:password',
            'admin_password' => 'required | min:7 | different:email'
        ];

        return $rules;
    }


    /*protected function confirmLoginStateRules(): array
    {
        $rules =  [
            'unique_admin_id'=>'required | exists:admins',
        ];
        return $rules;
    }*/

    protected function changePasswordRules(): array
    {
        //set validation rules:
        $rules = [
            'admin_email' => 'required | different:new_password | exists:admins',
            'new_password' => 'required | different:admin_email'
        ];

        return $rules;
    }


    protected function sendRequestPassLinkRules(): array
    {
        $rules = [
            'admin_email' => 'required | email',
        ];
        return $rules;
    }


    protected function resetPasswordRules(): array
    {
        $rules = [
            'token' => 'required',
            'admin_email' => 'required | email',
            'admin_password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
        return $rules;
    }


    protected function editRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_admin_id' => 'required | unique:admins',
            'admin_org' => 'nullable | string | min:5 | max:1000',
            'vision' => 'nullable | string | different:admin_org',
            'mission' => 'nullable | string | different:vision,admin_org',
            'year_of_establishment' => 'nullable | numeric',
            'industry' => 'nullable',
        ];

        return $rules;
    }


    protected function filesAndImagesRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_admin_id' => 'required | unique: admins',
            'logo' => 'nullable | image | mimes:jpg,png | dimensions:min_width=100,max_width=200,min_height=200,max_height=400',
        ];

        return $rules;
    }


    protected function logoutRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_admin_id' => 'required' //| exists: admins',
        ];

        return $rules;
    }

}

?>