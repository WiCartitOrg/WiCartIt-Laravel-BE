<?php

namespace App\Services\Traits\ModelAbstractions\Buyer;;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use App\Services\Traits\ModelCRUDs\Buyer\BuyerCRUD;
use App\Services\Traits\ModelCRUDs\General\CartCRUD;
use App\Services\Traits\ModelCRUDs\General\PaymentDetailCRUD;
use App\Services\Traits\ModelCRUDs\General\PaymentTransactionCRUD;

//use App\Services\Hooks\StripePaymentHook;
use App\Services\Hooks\LocalEngine\MonnifyPaymentHook;

trait BuyerPaymentExecuteAbstraction
{
	//inherits all their methods:
	use CartCRUD;
	use BuyerCRUD;
	use PaymentDetailCRUD;
	use PaymentTransactionCRUD;

	//Payment Engines:
	//use StripePaymentHook;
	use MonnifyPaymentHook;

	protected function BuyerMakePaymentWithNewCardService(Request $request): array 
	{
		//init:
		//first get the specific card details of this buyer:
		$queryKeysValues = ['unique_buyer_id' => $request?->unique_buyer_id];

		$buyerDetails = $this?->BuyerReadSpecificService($queryKeysValues);

		$userNewCardAndCartDetails = [];
		
		$userNewCardAndCartDetails['customer'] = $request?->unique_buyer_id;
		//first call all card details and put them in an array:
		$userNewCardAndCartDetails['buyer_card_type'] = $request?->buyer_card_type;
		$userNewCardAndCartDetails['buyer_card_number'] = $request?->buyer_card_number;
		$userNewCardAndCartDetails['buyer_card_cvv'] = $request?->buyer_card_cvv;
		$userNewCardAndCartDetails['buyer_card_exp_year'] = $request?->buyer_card_exp_year;
		$userNewCardAndCartDetails['buyer_card_exp_month'] = $request?->buyer_card_exp_month;

		$userNewCardAndCartDetails['buyer_email'] = $buyerDetails?->buyer_email;

		$cartQueryKeysValues = [
			'unique_buyer_id' => $request?->unique_buyer_id,
			'unique_cart_id' => $request?->unique_cart_id
		];

		$cartModel = $this?->CartReadSpecificService($cartQueryKeysValues);
		$userNewCardAndCartDetails['cart_payment_currency'] = $cartModel?->cart_payment_currency;

		$cart_total_cost = $cartModel?->cart_total_cost;
		$buyer_total_referral_bonus = $buyerDetails?->buyer_total_referral_bonus;

		$userNewCardDetails['charge_price'] = $cart_total_cost - $buyer_total_referral_bonus;

		$userNewCardDetails['pending_cart_id'] = $request?->unique_cart_id;

		
		//call our payment hooks that will interact with the API:
		$payment_was_made = $this?->CallMonnifyPayWithCardService($userNewCardDetails);
		if(!$payment_was_made)
		{
			return false;
		}
			//change the cart state from pending to cleared:
			$cartQueryKeysValues  = [
				'unique_buyer_id' => $request?->unique_buyer_id, 
				'unique_cart_id' => $request?->unique_cart_id
			];
			$newKeysValues = ['cart_payment_status' => 'cleared'];
			$this?->CartUpdateSpecificService($cartQueryKeysValues , $newKeysValues);

			//make the referral bonus equal to null because it has been used:
			$newKeysValues = ['buyer_total_referral_bonus' => null];
			$this?->BuyerUpdateSpecificService($queryKeysValues, $newKeysValues);
		
		return [
			'payment_was_made' => $payment_was_made,
			'unique_cart_id' => $request?->unique_cart_id,
			'purchase_currency' => $userNewCardAndCartDetails['cart_payment_currency'],
			'total_price' => $cart_total_cost,
			'discount_previous_referral_bonus' => $buyer_total_referral_bonus,
			'final_charge_price' => $userNewCardDetails['charge_price'],
			'payment_card_type' => base64_encode($userNewCardAndCartDetails['buyer_card_type']),
			'payment_card_number' => base64_encode($userNewCardAndCartDetails['buyer_card_number']),
		];
		

		//later: will record this in a transaction table before returning....
	}


	protected function BuyerMakePaymentWithSavedCardService(Request $request): array 
	{
		//init:

		//first get specific buyer details:
		$queryKeysValues1 = [
			'unique_buyer_id' => $request?->unique_buyer_id,
		];
		$buyerDetails = $this?->BuyerReadSpecificService($queryKeysValues1);

		//get the specific card details of this buyer:
		$queryKeysValues2 = [
			'owner' => 'buyer',
			'unique_owner_id' => $request?->unique_buyer_id
		];
		$paymentDetails = $this?->PaymentDetailReadSpecificService($queryKeysValues2);

		$userSavedCardAndCartDetails = [];
		
		$userSavedCardAndCartDetails['customer'] = $request?->unique_buyer_id;
		//first call all saved card details and put them in an array:
		$userSavedCardAndCartDetails['buyer_card_type'] = Crypt::decryptString($paymentDetails?->buyer_bank_card_type);
		$userSavedCardAndCartDetails['buyer_card_number'] = Crypt::decryptString($paymentDetails?->buyer_bank_card_number);
		$userSavedCardAndCartDetails['buyer_card_cvv'] = Crypt::decryptString($paymentDetails?->buyer_bank_card_cvv);
		$userSavedCardAndCartDetails['buyer_card_exp_year'] =  Crypt::decryptString($paymentDetails?->buyer_bank_card_expiry_year);
		$userSavedCardAndCartDetails['buyer_card_exp_month'] = Crypt::decryptString($paymentDetails?->buyer_bank_card_expiry_month);

		$userSavedCardAndCartDetails['buyer_email'] = $buyerDetails?->buyer_email;

		$cartQueryKeysValues = [
			'unique_buyer_id' => $request?->unique_buyer_id,
			'unique_cart_id' => $request?->unique_cart_id,
		];

		$cartModel = $this?->CartReadSpecificService($cartQueryKeysValues);
		$userSavedCardAndCartDetails['cart_purchase_currency'] = $cartModel?->cart_payment_currency;

		$cart_total_cost = $cartModel?->cart_total_cost;
		$buyer_total_referral_bonus = $buyerDetails?->buyer_total_referral_bonus;

		$userCardDetails['charge_price'] = $cart_total_cost - $buyer_total_referral_bonus;

		$userCardDetails['pending_cart_id'] = $request?->unique_cart_id;

		
		//call our payment hooks that will interact with the API:
		$payment_was_made = $this?->CallMonnifyPayWithCardService($userSavedCardAndCartDetails);
		if(!$payment_was_made)
		{
			return false;
		}
			//change the cart state from pending to cleared:
			$cartQueryKeysValues  = [
				'unique_buyer_id' => $request?->unique_buyer_id, 
				'unique_cart_id' => $request?->unique_cart_id
			];
			$newKeysValues = ['cart_payment_status' => 'cleared'];
			$this?->CartUpdateSpecificService($cartQueryKeysValues , $newKeysValues);

			//make the referral bonus equal to null because it has been used:
			$newKeysValues = ['buyer_total_referral_bonus' => null];
			$this?->BuyerUpdateSpecificService($queryKeysValues1, $newKeysValues);
		
		return [
			'payment_was_made' => $payment_was_made,
			'unique_cart_id' => $request?->unique_cart_id,
			'purchase_currency' => $userSavedCardAndCartDetails['cart_payment_currency'],
			'total_price' => $cart_total_cost,
			'discount_previous_referral_bonus' => $buyer_total_referral_bonus,
			'final_charge_price' => $userSavedCardAndCartDetails['charge_price'],
			'payment_card_type' => base64_encode($userSavedCardAndCartDetails['buyer_card_type']),
			'payment_card_number' => base64_encode($userSavedCardAndCartDetails['buyer_card_number']),
		];
		
		//later: will record this in a transaction table before returning....
	}


	
	protected function BuyerMakePaymentWithNewBankService(Request $request): array 
	{
		//init:
		//first get the specific bank details of this buyer:
		$queryKeysValues = ['unique_buyer_id' => $request?->unique_buyer_id];

		$buyerDetails = $this?->BuyerReadSpecificService($queryKeysValues);

		$userNewBankAndCartDetails = [];

		$userNewBankAndCartDetails['customer'] = $request?->unique_buyer_id;

		//first call all bank details and put them in an array:
		$userNewBankAndCartDetails['bank_account_first_name'] = $request?->bank_account_first_name;
		$userNewBankAndCartDetails['bank_account_middle_name'] = $request?->bank_account_middle_name;
		$userNewBankAndCartDetails['bank_account_last_name'] = $request?->bank_account_last_name;
		$userNewBankAndCartDetails['bank_account_type'] =  $request?->bank_account_type;
		$userNewBankAndCartDetails['bank_account_number'] = $request?->bank_account_number;
		$userNewBankAndCartDetails['bank_name'] = $request?->bank_name;

		$userNewBankAndCartDetails['buyer_email'] = $buyerDetails?->buyer_email;

		$cartQueryKeysValues = [
			'unique_buyer_id' => $request?->unique_buyer_id,
			'unique_cart_id' => $request?->unique_cart_id
		];

		$cartModel = $this?->CartReadSpecificService($cartQueryKeysValues);
		$userNewBankAndCartDetails['cart_purchase_currency'] = $cartModel?->purchase_currency;

		$cart_total_cost = $cartModel?->cart_total_cost;
		$buyer_total_referral_bonus = $buyerDetails?->buyer_total_referral_bonus;

		$userNewBankAndCartDetails['charge_price'] = $cart_total_cost - $buyer_total_referral_bonus;

		$userNewBankAndCartDetails['pending_cart_id'] = $request?->unique_cart_id;

		
		//call our payment hooks that will interact with the API:
		$payment_was_made = $this?->CallMonnifyPayWithBankService($userNewBankAndCartDetails);
		if(!$payment_was_made)
		{
			return false;
		}

			//change the cart state from pending to cleared:
			$cartQueryKeysValues  = [
				'unique_buyer_id' => $request?->unique_buyer_id, 
				'unique_cart_id' => $request?->unique_cart_id
			];
			$newKeysValues = ['cart_payment_status' => 'cleared'];
			$this?->CartUpdateSpecificService($cartQueryKeysValues , $newKeysValues);

			//make the referral bonus equal to null because it has been used:
			$newKeysValues = ['buyer_total_referral_bonus' => null];
			$this?->BuyerUpdateSpecificService($queryKeysValues, $newKeysValues);
		
		return [
			'payment_was_made' => $payment_was_made,
			'unique_cart_id' => $request?->unique_cart_id,
			'purchase_currency' => $userNewBankAndCartDetails['cart_payment_currency'],
			'total_price' => $cart_total_cost,
			'discount_previous_referral_bonus' => $buyer_total_referral_bonus,
			'final_charge_price' => $userNewBankAndCartDetails['charge_price'],
			'bank_account_type' => base64_encode($userNewBankAndCartDetails['bank_account_type']),
			'bank_account_number' => base64_encode($userNewBankAndCartDetails['bank_account_number']),
		];
		
		//later: will record this in a transaction table before returning....
	}


	protected function BuyerMakePaymentWithSavedBankService(Request $request): array 
	{
		//init:
		//first get specific buyer details:
		$queryKeysValues1 = [
			'unique_buyer_id' => $request?->unique_buyer_id,
		];
		$buyerDetails = $this?->BuyerReadSpecificService($queryKeysValues1);
	
		//get the specific card details of this buyer:
		$queryKeysValues2 = [
			'owner' => 'buyer',
			'unique_owner_id' => $request?->unique_buyer_id
		];
		$paymentDetails = $this?->PaymentDetailReadSpecificService($queryKeysValues2);

		$userNewBankAndCartDetails = [];
		
		$userNewBankAndCartDetails['customer'] = $request?->unique_buyer_id;
		//first call all bank details and put them in an array:
		$userNewBankAndCartDetails['buyer_bank_type'] = Crypt::decryptString($paymentDetails?->buyer_bank_bank_type);
		$userNewBankAndCartDetails['buyer_bank_number'] = Crypt::decryptString($paymentDetails?->buyer_bank_bank_number);
		$userNewBankAndCartDetails['buyer_bank_cvv'] = Crypt::decryptString($paymentDetails?->buyer_bank_bank_cvv);
		$userNewBankAndCartDetails['buyer_bank_exp_year'] =  Crypt::decryptString($paymentDetails?->buyer_bank_bank_expiry_year);
		$userNewBankAndCartDetails['buyer_bank_exp_month'] = Crypt::decryptString($paymentDetails?->buyer_bank_bank_expiry_month);

		$userNewBankAndCartDetails['buyer_email'] = $buyerDetails?->buyer_email;

		$cartQueryKeysValues = [
			'unique_buyer_id' => $request?->unique_buyer_id,
			'unique_cart_id' => $request?->unique_cart_id
		];

		$cartModel = $this?->CartReadSpecificService($cartQueryKeysValues);
		$userNewBankAndCartDetails['cart_purchase_currency'] = $cartModel?->purchase_currency;

		$cart_total_cost = $cartModel?->cart_total_cost;
		$buyer_total_referral_bonus = $buyerDetails?->buyer_total_referral_bonus;

		$userNewBankAndCartDetails['charge_price'] = $cart_total_cost - $buyer_total_referral_bonus;

		$userNewBankAndCartDetails['pending_cart_id'] = $request?->unique_cart_id;

		
		//call our payment hooks that will interact with the API:
		$payment_was_made = $this?->CallMonnifyPayWithBankService($userNewBankAndCartDetails);
		if(!$payment_was_made)
		{
			return false;
		}

			//change the cart state from pending to cleared:
			$cartQueryKeysValues  = [
				'unique_buyer_id' => $request?->unique_buyer_id, 
				'unique_cart_id' => $request?->unique_cart_id
			];
			$newKeysValues = ['cart_payment_status' => 'cleared'];
			$this?->CartUpdateSpecificService($cartQueryKeysValues , $newKeysValues);

			//make the referral bonus equal to null because it has been used:
			$newKeysValues = ['buyer_total_referral_bonus' => null];
			$this?->BuyerUpdateSpecificService($queryKeysValues1, $newKeysValues);
		
		return [
			'payment_was_made' => $payment_was_made,
			'unique_cart_id' => $request?->unique_cart_id,
			'purchase_currency' => $userNewBankAndCartDetails['cart_payment_currency'],
			'total_price' => $cart_total_cost,
			'discount_previous_referral_bonus' => $buyer_total_referral_bonus,
			'final_charge_price' => $userNewBankAndCartDetails['charge_price'],
			'bank_account_type' => base64_encode($userNewBankAndCartDetails['bank_account_type']),
			'bank_account_number' => base64_encode($userNewBankAndCartDetails['bank_account_number']),
		];
		
		//later: will record this in a transaction table before returning....
	}

}