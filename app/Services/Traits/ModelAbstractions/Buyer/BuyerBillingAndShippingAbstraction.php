<?php

namespace App\Services\Traits\ModelAbstractions\Buyer;

use Illuminate\Http\Request;

use App\Services\Traits\ModelCRUDs\Buyer\BuyerBillingCRUD;
use App\Services\Traits\ModelCRUDs\Buyer\BuyerShippingCRUD;
use App\Models\Buyer\BuyerBilling;
use App\Models\Buyer\BuyerShipping;

trait BuyerBillingAndShippingAbstraction
{
	use BuyerBillingCRUD;
	use BuyerShippingCRUD;

	//Create and Update: 
	protected function BuyerSaveBillingDetailsService(Request $request): bool
	{
		$details_saved_status = false;

		//now first get the buyer token id:
		$queryKeysValues = ['unique_buyer_id' => $request?->unique_buyer_id];

		//Check if Billing Details table is not empty:
		$buyerBillingDetail = $this?->BuyerBillingReadSpecificService($queryKeysValues);
		if( $buyerBillingDetail?->count() !== 0 )
		{	
			//then update if the record exists: 
			$newKeysValues = $request?->except('unique_buyer_id');
			//call the update function:
			$is_details_saved = $this?->BuyerBillingUpdateSpecificService($queryKeysValues, $newKeysValues);
			$details_saved_status = $is_details_saved;
		}
		else
		{
			//create new record:
			$params_to_be_saved = $request?->all();
			//save all using mass assignment:
			$is_details_saved = $this?->BuyerBillingCreateAllService($params_to_be_saved);

			$details_saved_status = $is_details_saved;
		}

		return $details_saved_status;	
	}


	protected function BuyerFetchBillingDetailsService(Request $request): BuyerBilling | null
	{
		$queryKeysValues = ['unique_buyer_id' => $request?->unique_buyer_id];
		$specificBillingDetail = $this?->BuyerBillingReadSpecificService($queryKeysValues);

		return $specificBillingDetail;
	}


	protected function BuyerSaveShippingDetailsService(Request $request): bool
	{
		$details_saved_status = false;

		//now first get the buyer token id:
		$queryKeysValues = ['unique_buyer_id' => $request?->unique_buyer_id];

		//Check if Shipping Details table is not empty:
		$buyerBillingDetail = $this?->BuyerShippingReadSpecificService($queryKeysValues);

		if( $buyerBillingDetail?->count() !== 0 )
		{
			//then update if the record exists: 
			$newKeysValues = $request?->except('unique_buyer_id');
			//call the update function:
			$is_details_saved = $this?->BuyerShippingUpdateSpecificService($queryKeysValues, $newKeysValues);
			$details_saved_status = $is_details_saved;
		}
		else
		{
			//create new record:
			$params_to_be_saved = $request?->all();
			//save all using mass assignment:
			$is_details_saved = $this?->BuyerShippingCreateAllService($params_to_be_saved);

			$details_saved_status = $is_details_saved;
		}

		return $details_saved_status;	
	}


	protected function BuyerFetchShippingDetailsService(Request $request): BuyerShipping | null
	{
		$queryKeysValues = ['unique_buyer_id' => $request?->unique_buyer_id];
		$allShippingDetail = $this?->BuyerShippingReadSpecificService($queryKeysValues);

		return $allShippingDetail;
	}


	protected function BuyerBillingAndShippingDeleteAllNullService(array $billingDeleteKeysValues, array $shippingDeleteKeysValues): bool
	{
		//init:
		$this?->BuyerBillingDeleteSpecificService($billingDeleteKeysValues);
		$this?->BuyerShippingDeleteSpecificService($shippingDeleteKeysValues);

		return true;
	}

}

?>