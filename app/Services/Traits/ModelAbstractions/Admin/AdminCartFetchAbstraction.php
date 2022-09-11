protected function VendorFetchCartsIDsOnlyService(Request $request): array
	{
		//init:
		$pending_summary = [
			'category' => 'pending_ids',
		];

		$cleared_summary = [
			'category' => 'cleared_ids',
		];

		//assign:
		$queryKeysValues1 = [
			'unique_vendor_id' => $request->unique_vendor_id,
			'cart_payment_status' => 'pending'
		];

		$queryKeysValues2 = [
			'unique_vendor_id' => $request->unique_vendor_id,
			'cart_payment_status' => 'cleared'
		];

		$all_pending_carts = $this->CartReadAllLazySpecificService($queryKeysValues1);
		$all_cleared_carts = $this->CartReadAllLazySpecificService($queryKeysValues2);

		//get only cart ids:
		$all_pending_carts_ids = $all_pending_carts->pluck('unique_cart_id');
		$all_cleared_cart_ids = $all_cleared_carts->pluck('unique_cart_id');

		//add to summary:
		$pending_summary['ids'] = $all_pending_carts_ids;
		$cleared_summary['ids'] = $all_cleared_cart_ids;
		
		return [
			json_encode($pending_summary),
			json_encode($cleared_summary),
		];
	}



	protected function VendorFetchAllCartProductsIDsOnlyService(Request $request): LazyCollection
	{
		//assign:
		$vendor_id = $request->unique_vendor_id;
		$cart_id = $request->unique_cart_id;

		//query:
		$queryKeysValues = [
			'unique_vendor_id' => $vendor_id,
			'unique_cart_id' => $cart_id,
		];

		//first get all the product_ids associated with this cart:
		$thisCartDetails =  $this->CartReadAllLazyService($queryKeysValues);
		$product_ids = $thisCartDetails->pluck('cart_attached_products_ids_quantities_quantities_quantities');//this should return in array(it's now casted)

		return	$product_ids;
	}

	protected function CartDeleteAllNullService(array $deleteKeysValues): bool
    {
		//init:
		$deleted_state = false;
    	$is_details_deleted = $this?->CartDeleteSpecificService($deleteKeysValues);
		if($is_details_deleted)
		{
			$deleted_state = true;
		}

		return $deleted_state;
    }