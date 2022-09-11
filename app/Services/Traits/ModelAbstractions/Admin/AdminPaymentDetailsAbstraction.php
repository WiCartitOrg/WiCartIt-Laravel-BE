public function BuyerFetchEachCardDetailsService(Request $request)
	{
		$cart_model = $this?->FetchPendingOrClearedCardDetails($request);
		if(!$cart_model)
		{
			throw new \Exception("Card Details not found! Ensure that this belongs to appropriate Card Category.");
		}

		//get the buyer id:
		$buyer_id = $cart_model?->unique_buyer_id;
		//now use this to get the buyer model:(this is in a bead to get buyer email and phone number)
		$queryKeysValues = ['unique_buyer_id' => $buyer_id];
		$buyer_model = $this?->BuyerReadSpecificService($queryKeysValues);
		
		//begin to prepare the return array:
		$cart_model['cart_created_at'] = $cart_model?->created_at;
		$cart_model['cart_updated_at'] = $cart_model?->updated_at;
		$cart_model['buyer_email'] = $buyer_model?->buyer_email;
		$cart_model['buyer_phone_number'] = $buyer_model?->buyer_phone_number;

		return $cart_model;
	}	

	protected function BuyerFetchAllCardIDsService(Request $request)
	{
	
		//assign:
		$queryKeysValues = ['payment_status' => $request?->payment_status];

		$all_carts_found = $this?->CardReadAllLazySpecificService($queryKeysValues);

		$all_unique_cart_ids = $all_carts_found?->pluck('unique_cart_id');

		return $all_unique_cart_ids;
	}