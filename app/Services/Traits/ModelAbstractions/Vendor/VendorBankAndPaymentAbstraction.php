<?php

namespace App\Services\Traits\ModelAbstractions\Vendor;

use Illuminate\Http\Request;
//use Illuminate\Database\Eloquent\Collection;

use App\Services\Traits\ModelCRUD\PaymentInfoCRUD;
use App\Services\Traits\ModelCRUD\VendorBankDetailCRUD;
//use App\Models\VendorBankDetail;

trait VendorBankandPaymentInfoAbstraction
{
	use PaymentInfoCRUD;
	use VendorBankDetailCRUD;

	protected function VendorSaveBankDetailsService(Request $request) //: bool
	{
		$details_saved_status = false;

		//first get if Business Details table is not empty:
		$vendorBankDetails = $this?->VendorBankDetailsReadAllService();
		if( $vendorBankDetails?->count() !== 0 )
		{
			//return "Cool";
			//now first get the Vendor token id:
			$token_id = $request?->token_id;

			//then update thus:
			$queryKeysValues = ['token_id' => $token_id];
			$newKeysValues = $request?->except('token_id');

			//call the update function:
			$is_details_saved = $this?->VendorBankDetailsUpdateSpecificService($queryKeysValues, $newKeysValues);

			$details_saved_status = $is_details_saved;
		}
		else
		//if( $vendorBankDetails?->count() == 0 )
		{
			//return "Cool Thingy";
			//else:
			$params_to_be_saved = $request?->all();
			//save all using mass assignment:
			$is_details_saved = $this?->VendorBankDetailsCreateAllService($params_to_be_saved);

			$details_saved_status = $is_details_saved;
		}

		return $details_saved_status;	
	}


	protected function VendorFetchBankDetailsService(Request $request)
	{
		$queryKeysValues = ['token_id' => $request?->token_id];
		$allBizDetails = $this?->VendorBankDetailsReadSpecificService($queryKeysValues);

		return $allBizDetails;
	}
}

?>