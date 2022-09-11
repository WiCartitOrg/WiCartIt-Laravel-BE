protected function getSalesDataRules(): array
    {
		//set validation rules:
        $rules = [
            'vendor_id'=> 'required | unique:vendors'
        ];

        return $rules;
    }

    protected function viewFrequentRules(): array
    {
        //set validation rules:
        $rules = [
            'vendor_id'=> 'required | unique:vendors',
            'is_cleared' => 'required | bool',
        ];

        return $rules;
    }