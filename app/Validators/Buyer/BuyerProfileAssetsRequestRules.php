<?php

namespace App\Validators\Buyer;

use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Password;

trait BuyerProfileAssetsRequestRules {

    protected function editRules(): array
    {
        //set validation rules:
        $rules = [
            'buyer_id' => 'required | unique:buyers',
            'means_of_payment' => 'nullable',//credit card, online transfer, bitcoin
            
            /*Your credit card info is PCII compliant and safe with us, even we don't know it*/
            'card_name' => 'nullable',
            'card_number' => 'nullable',
            'card_cvv' => 'nullable',
        ];

        return $rules;

    }

    protected function filesAndImagesRules(): array
    {
        //set validation rules:
        $rules = [
            'buyer_id' => 'required | unique: buyers',
            'profile_picture' => 'nullable | image | mimes:jpg,png | dimensions:min_width=100,max_width=200,min_height=200,max_height=400',
        ];

        return $rules;
    }

}