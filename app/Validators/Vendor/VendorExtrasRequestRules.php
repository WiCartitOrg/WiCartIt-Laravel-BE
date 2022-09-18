<?php

namespace App\Validators\Vendor;

trait AdminExtrasRequestRules 
{
    protected function fetchGeneralStatisticsRules(): array
    {
        //set validation rules:
        $rules = [
            'token_id' => 'required | string | exists:admins',
        ];

        return $rules;
    }

}