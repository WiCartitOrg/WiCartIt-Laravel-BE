<?php

namespace App\Services\Traits\ModelAbstractions\Buyer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use App\Services\Traits\ModelCRUDs\General\PaymentDetailCRUD;

trait BuyerPaymentDetailsAbstraction
{
	//inherits all their methods:
	use PaymentDetailCRUD;

	protected function BuyerUploadCardDetailsService(Request $request): bool
	{
		$details_saved_status = null;

		$buyer_id = $request?->unique_buyer_id;

			//first try to update:
			$queryKeysValues = [
				'owner' => 'buyer',
				'unique_owner_id' => $buyer_id,
			];
			//Check if Payment Details table is not empty:
			$buyerPaymentDetail = $this?->PaymentDetailReadSpecificService($queryKeysValues);

			if( $buyerPaymentDetail?->count() !== 0 )
			{
				//then update if the record exists: 
				$newKeysValues = $request?->except('unique_buyer_id');
				//try to update function:
				//loop through:
				foreach($newKeysValues as $cardKey=>$cardValue)
				{
					//encrypt each values:
					$encCardValue = Crypt::encryptString($cardValue);
					$newKeywithEncValue = [$cardKey => $encCardValue];
					//save where:
					$card_details_was_updated = $this?->PaymentDetailUpdateSpecificService($queryKeysValues, $newKeywithEncValue);
					$details_saved_status = $card_details_was_updated;
				}
			}
			else
			{
				//create new record:
				$params_to_be_saved = $request?->except('unique_buyer_id');
				//step through, encrypt all values
				foreach($params_to_be_saved as $cardKey => $cardValue)
				{
					$params_to_be_saved[$cardKey] = Crypt::encryptString($cardValue);
				}
				$params_to_be_saved['owner'] = 'buyer';
				$params_to_be_saved['unique_owner_id'] = $request?->unique_buyer_id;

				//save all using mass assignment:
				$card_details_was_created = $this?->PaymentDetailCreateAllService($params_to_be_saved);

				$details_saved_status = $card_details_was_created;
			}

		return $details_saved_status;
	}



	protected function BuyerFetchEachCardDetailsService(Request $request): array
	{
		//init:
		$card_details = array();

		$buyer_id = $request?->unique_buyer_id;
		$queryKeysValues = [
			'owner' => 'buyer',
			'unique_owner_id' => $buyer_id,
		];
		$buyer_payment_detail_object = $this?->PaymentDetailReadSpecificService($queryKeysValues);

		//prepare final return:
		$card_details['card_type'] = Crypt::decryptString($buyer_payment_detail_object?->buyer_bank_card_type);
		$card_details['card_number'] = Crypt::decryptString($buyer_payment_detail_object?->buyer_bank_card_number);
		$card_details['card_cvv'] = Crypt::decryptString($buyer_payment_detail_object?->buyer_bank_card_cvv);
		$card_details['exp_month'] = Crypt::decryptString($buyer_payment_detail_object?->buyer_bank_card_expiry_month);
		$card_details['exp_year'] = Crypt::decryptString($buyer_payment_detail_object?->buyer_bank_card_expiry_year);

		return $card_details;
	}



	protected function BuyerUploadAccountDetailsService(Request $request): bool
	{
		$details_saved_status = null;

		$buyer_id = $request?->unique_buyer_id;
		//first try to update:
		$queryKeysValues = [
			'owner' => 'buyer',
			'unique_owner_id' => $buyer_id,
		];
		//Check if Payment Details table is not empty:
		$buyerPaymentDetail = $this?->PaymentDetailReadSpecificService($queryKeysValues);

		if( $buyerPaymentDetail?->count() !== 0 )
		{
			//then update if the record exists: 
			$newKeysValues = $request?->except('unique_buyer_id');
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
			$params_to_be_saved = $request?->except('unique_buyer_id');
			//step through, encrypt all values
			foreach($params_to_be_saved as $accountKey => $accountValue)
			{
				$params_to_be_saved[$accountKey] = Crypt::encryptString($accountValue);
			}
			$params_to_be_saved['owner'] = 'buyer';
			$params_to_be_saved['unique_owner_id'] = $request?->unique_buyer_id;

			//save all using mass assignment:
			$account_details_was_created = $this?->PaymentDetailCreateAllService($params_to_be_saved);

			$details_saved_status = $account_details_was_created;
		}

		return $details_saved_status;
	}



	protected function BuyerFetchEachAccountDetailsService(Request $request): array
	{
		//init:
		$account_details = array();

		$buyer_id = $request?->unique_buyer_id;
		$queryKeysValues = [
			'owner' => 'buyer',
			'unique_owner_id' => $buyer_id,
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

}