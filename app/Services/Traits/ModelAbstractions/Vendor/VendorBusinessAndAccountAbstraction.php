<?php
namespace App\Services\Traits\ModelAbstractions\Vendor;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use App\Services\Traits\ModelCRUDs\Vendor\VendorGenBizCRUD;
use App\Services\Traits\ModelCRUDs\General\PaymentDetailCRUD;


trait VendorBusinessAndAccountAbstraction 
{
	use VendorGenBizCRUD;
	use PaymentDetailCRUD;


	protected function VendorUploadAccountDetailsService(Request $request): bool
	{
		$details_saved_status = null;

		$vendor_id = $request?->unique_vendor_id;

		//first try to update:
		$queryKeysValues = [
			'owner' => 'vendor',
			'unique_owner_id' => $vendor_id,
		];
		//Check if Payment Details table is not empty:
		$vendorPaymentDetail = $this?->PaymentDetailReadSpecificService($queryKeysValues);

		if( $vendorPaymentDetail?->count() !== 0 )
		{
			//then update if the record exists: 
			$newKeysValues = $request?->except('unique_vendor_id');
			//try to update function:
			//loop through:
			foreach($newKeysValues as $accountKey=>$accountValue)
			{
				//encrypt each values:
				$encAccountValue = Crypt::encryptString($accountValue);
				$newKeywithEncValue = [$accountKey => $encAccountValue];
				//save where:
				$account_details_was_updated = $this?->PaymentDetailUpdateSpecificService($queryKeysValues, $newKeywithEncValue);
				$details_saved_status = $account_details_was_updated;
			}
		}
		else
		{
			//create new record:
			$params_to_be_saved = $request?->except('unique_vendor_id');
			//step through, encrypt all values
			foreach($params_to_be_saved as $accountKey => $accountValue)
			{
				$params_to_be_saved[$accountKey] = Crypt::encryptString($accountValue);
			}
			$params_to_be_saved['owner'] = 'vendor';
			$params_to_be_saved['unique_owner_id'] = $request?->unique_vendor_id;

			//save all using mass assignment:
			$account_details_was_created = $this?->PaymentDetailCreateAllService($params_to_be_saved);

			$details_saved_status = $account_details_was_created;
		}

		return $details_saved_status;
	}

	protected function VendorFetchEachBankAccountDetailsService(Request $request): array
	{
		//init:
		$account_details = array();

		$vendor_id = $request?->unique_vendor_id;
		$queryKeysValues = [
			'owner' => 'vendor',
			'unique_owner_id' => $vendor_id,
		];
		$buyer_payment_detail_object = $this?->PaymentDetailReadSpecificService($queryKeysValues);

		//prepare final return:
		$account_details['bank_account_first_name'] = Crypt::decryptString($buyer_payment_detail_object?->bank_account_first_name);
		$account_details['bank_account_middle_name'] = Crypt::decryptString($buyer_payment_detail_object?->bank_account_middle_name);
		$account_details['bank_account_last_name'] = Crypt::decryptString($buyer_payment_detail_object?->bank_account_last_name);
		$account_details['bank_account_type'] = Crypt::decryptString($buyer_payment_detail_object?->bank_account_type);
		$account_details['bank_account_number'] = Crypt::decryptString($buyer_payment_detail_object?->bank_account_number);
		$account_details['bank_name'] = Crypt::decryptString($buyer_payment_detail_object?->bank_name);

		return $account_details;
	}


    protected function VendorUploadBusinessDetailsService(Request $request): bool
	{
		$details_saved_status = false;

		//first get if Business Details table is not empty:
		$VendorBizDetails = $this?->VendorGenBizReadAllService();
		if( $VendorBizDetails?->count() !== 0)
		{
			//now first get the Vendor token id:
			$token_id = $request?->unique_vendor_id;

			//then update thus:
			$queryKeysValues = ['unique_vendor_id' => $token_id];
			$newKeysValues = $request?->except('token_id');

			//call the update function:
			$is_details_saved = $this?->VendorGenBizUpdateSpecificService($queryKeysValues, $newKeysValues);

			$details_saved_status = $is_details_saved;
		}
		else
		{
			//else:
			$params_to_be_saved = $request?->all();
			//save all using mass assignment:
			$is_details_saved = $this?->VendorGenBizCreateAllService($params_to_be_saved);

			$details_saved_status = $is_details_saved;
		}

		return $details_saved_status;
	}


	protected function VendorFetchBusinessDetailsService(Request $request)
	{
		$queryKeysValues = ['unique_vendor_id' => $request?->unique_vendor_id];
		$bizDetails = $this?->VendorGenBizReadSpecificService($queryKeysValues);

		return $bizDetails;
	}
}

?>