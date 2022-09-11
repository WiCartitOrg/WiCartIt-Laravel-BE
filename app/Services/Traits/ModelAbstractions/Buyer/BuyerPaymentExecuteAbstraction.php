<?php

namespace App\Services\Traits\ModelAbstractions\Buyer;;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use App\Services\Traits\ModelCRUD\CartCRUD;
use App\Services\Traits\ModelCRUD\BuyerCRUD;
use App\Services\Traits\ModelCRUD\PaymentTransactionCRUD;

use App\Services\Hooks\StripePaymentHook;

trait BuyerPaymentExecuteAbstraction
{
	//inherits all their methods:
	use CartCRUD;
	use BuyerCRUD;
	use PaymentTransactionCRUD;
	use StripePaymentHook;

	protected function BuyerMakePaymentService(Request $request): array 
	{
		//init:
		//first get the specific card details of this buyer:
		$queryKeysValues = ['unique_buyer_id' => $request->unique_buyer_id];

		$buyerDetails = $this->BuyerReadSpecificService($queryKeysValues);

		$userCardDetails = [];
		
		$userCardDetails['customer'] = $request->unique_buyer_id;
		//first call all card details and put them in an array:
		$userCardDetails['buyer_card_type'] = Crypt::decryptString($buyerDetails?->buyer_bank_card_type);
		$userCardDetails['buyer_card_number'] = Crypt::decryptString($buyerDetails?->buyer_bank_card_number);
		$userCardDetails['buyer_card_cvv'] = Crypt::decryptString($buyerDetails?->buyer_bank_card_cvv);
		$userCardDetails['buyer_card_exp_year'] =  Crypt::decryptString($buyerDetails?->buyer_bank_card_expiry_year);
		$userCardDetails['buyer_card_exp_month'] = Crypt::decryptString($buyerDetails?->buyer_bank_card_expiry_month);

		$userCardDetails['buyer_email'] = $buyerDetails?->buyer_email;

		$cartQueryKeysValues = [
			'unique_buyer_id' => $request->unique_buyer_id,
			'unique_cart_id' => $request->unique_cart_id
		];

		$cartModel = $this->CartReadSpecificService($cartQueryKeysValues);
		$userCardDetails['cart_purchase_currency'] = $cartModel?->purchase_currency;

		$cart_purchase_price = $cartModel?->purchase_price;
		$buyer_total_referral_bonus = $buyerDetails?->buyer_total_referral_bonus;

		$userCardDetails['charge_price'] = $cart_purchase_price - $buyer_total_referral_bonus;

		$userCardDetails['pending_cart_id'] = $request->unique_cart_id;

		
		//call our payment hooks that will interact with the API:
		$is_payment_made = $this->CallStripeService($userCardDetails);
		if($is_payment_made)
		{
			//change the cart state from pending to cleared:
			$cartQueryKeysValues  = [
				'unique_buyer_id' => $request->unique_buyer_id, 
				'unique_cart_id' => $request->unique_cart_id
			];
			$newKeysValues = ['payment_status' => 'cleared'];
			$this->CartUpdateSpecificService($cartQueryKeysValues , $newKeysValues);

			//make the referral bonus equal to null because it has been used:
			$newKeysValues = ['buyer_total_referral_bonus' => null];
			$this->BuyerUpdateSpecificService($queryKeysValues, $newKeysValues);
		}
		
		return [
			'is_payment_made' => $is_payment_made,
			'unique_cart_id' => $request->unique_cart_id,
			'purchase_currency' => $userCardDetails['cart_purchase_currency'],
			'purchase_price' => $cart_purchase_price,
			'discount' => $buyer_total_referral_bonus
		];
			
		//return $userCardDetails;
	}
}