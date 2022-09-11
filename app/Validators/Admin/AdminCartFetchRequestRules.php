protected function fetchCartsIDsOnlyRules(): array
    {
         //set validation rules:
         $rules = [
            'unique_vendor_id' => 'required | string | size:10 | exists:vendors', 
            'cart_payment_status' => 'required | string', //pending or cleared
        ];

        return $rules; 
    }

    protected function fetchCartProductsIDsOnlyRules(): array
    {
        //set validation rules:
        $rules = [
            'unique_vendor_id' => 'required | string | size:10 | exits:buyers', 
            'unique_cart_id' => 'required | string | size:15 | exists:carts',
        ];

        return $rules;   
    }