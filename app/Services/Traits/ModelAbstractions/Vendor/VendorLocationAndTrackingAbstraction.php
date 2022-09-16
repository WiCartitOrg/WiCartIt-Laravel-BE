<?php

namespace App\Services\Traits\ModelAbstractions\Vendor;

use Illuminate\Http\Request;
//use Illuminate\Database\Eloquent\Collection;

use App\Services\Traits\ModelCRUD\ProductLocationAndTrackingCRUD;
use App\Services\Traits\ModelCRUD\CartCRUD;

trait VendorLocationsAndTracksAbstraction
{
	use ProductLocationAndTrackingCRUD;
	use CartCRUD;

	protected function VendorSaveLocationsAndTracksService(Request $request) //: bool
	{
		$details_saved_status = false;
		$queryKeysValues = [];

		$queryKeysValues = ['unique_cart_id' => $request?->unique_cart_id];

		//first check the status of this count_id: 
		$cartDetails = $this?->CartReadSpecificService($queryKeysValues);
		if(!$cartDetails)
		{
			throw new \Exception("Cart ID does not exist!");
		}
		else if($cartDetails?->payment_status === 'pending')
		{
			throw new \Exception("This Cart is pending! It hasn't been shipped yet!");
		}
		else if($cartDetails?->payment_status === 'cleared')
		{
			//first get if the track table is not empty:
			$locationTrackDetails = $this?->ProductLocationAndTrackingReadSpecificService($queryKeysValues);
			if($locationTrackDetails)
			{
				//return "Cool";
				//now first get the Vendor token id:

				$newKeysValues = $request?->except(['unique_cart_id', 'token_id']);

				//call the update function:
				$is_details_saved = $this?->ProductLocationAndTrackingUpdateSpecificService($queryKeysValues, $newKeysValues);

				$details_saved_status = $is_details_saved;
			}
			else
			//if(!$locationTrackDetails)
			{
				//return "Cool Thingy";
				//else:
				$params_to_be_saved = $request?->except('token_id');
				//save all using mass assignment:
				$is_details_saved = $this?->LocationsAndTracksCreateAllService($params_to_be_saved);

				$details_saved_status = $is_details_saved;
			}
		}
		
		return $details_saved_status;	
	}


	protected function VendorFetchLocationsAndTracksService(Request $request)
	{
		$queryKeysValues = ['unique_cart_id' => $request?->unique_cart_id];

		//fetch the cart details(whose aim is to fetch the purchase currency and price from db):
		$cartDetails = $this?->CartReadSpecificService($queryKeysValues);
		if(!$cartDetails)
		{
			throw new \Exception("Cart ID does not exist!");
		}
		else if($cartDetails?->payment_status === 'pending')
		{
			throw new \Exception("This Cart is pending! It hasn't been shipped yet!");
		}

		//now fetch all locations details:
		$allLocationDetails = $this?->ProductLocationAndTrackingReadSpecificService($queryKeysValues);

		//add purchase currency and price
		$allLocationDetails['purchase_currency'] = $cartDetails?->purchase_currency;
		$allLocationDetails['purchase_price'] = $cartDetails?->purchase_price;

		return $allLocationDetails;
	}
}

?>