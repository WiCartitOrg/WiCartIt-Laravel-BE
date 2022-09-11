<?php

namespace App\Services\Traits\ModelAbstractions\Buyer;;

use Illuminate\Http\Request;

use App\Services\Traits\ModelCRUDs\General\ProductCRUD;
use App\Services\Traits\ModelCRUDs\General\CartCRUD;
use App\Services\Traits\ModelCRUDs\Buyer\BuyerCRUD;
use App\Services\Traits\ModelAbstractions\Buyer\BuyerProductAbstraction;

use App\Services\Traits\Utilities\ComputeUniqueIDService;

trait BuyerCartEditAbstraction
{
	//inherits all their methods:
	use ProductCRUD;
	use BuyerCRUD;
	use CartCRUD;
	use ComputeUniqueIDService;


	protected function BuyerAddProductToPendingCartService(Request $request) : bool
	{
		//extract the new array:
		$newKeysValues = [
			'unique_buyer_id' => $request->unique_buyer_id,
			'unique_cart_id' => $this->genUniqueNumericId(),
			'cart_products_count' => $request->cart_products_count,
			'cart_payment_currency' => $request->cart_payment_currency,
			'cart_products_cost' => $request->cart_products_cost,
			'cart_shipping_cost' => $request->cart_shipping_cost,
			'cart_total_cost' => $request->cart_total_cost,
			//this is currently expected to be in array, cast this into json before saving:
			'cart_attached_products_ids_quantities_quantities' => $request->cart_attached_products_ids_quantities_quantities,//format:['id1'=>quantities]
			'cart_payment_status' => $request->payment_status,//pending or cleared(defaults to pending)
			//'created_at'=> $request->created_at,
			//there's no need for this as eloquent returns "created_at" field by default...
		];

		//save:
		$is_cart_created = $this->CartCreateAllService($newKeysValues);
		return $is_cart_created;
	}


	protected function BuyerEditProductsOnPendingCartService(Request $request): bool
	{
		$buyer_id = $request->unique_buyer_id;
		$cart_id = $request->unique_cart_id;

		$request = $request->except('unique_buyer_id', 'unique_cart_id');

		foreach($request as $reqKey => $reqValue)
		{
			$queryKeysValues = [
				'unique_buyer_id' => $buyer_id,
				'unique_cart_id' => $cart_id,
				'cart_payment_status' => 'pending',
			];

			if(is_array($reqValue))
			{
				$newKeysValues = [$reqKey => json_encode($reqValue)];
			}
			else
			{
				$newKeysValues = [$reqKey => $reqValue];
			}

			$cartWasUpdated = $this->CartUpdateSpecificService($queryKeysValues, $newKeysValues);
			if(!$cartWasUpdated)
			{
				return false;
			}
		}

		return true;
	}
	

	protected function BuyerDeletePendingCartService(Request $request): bool
	{
		$buyer_id = $request->unique_buyer_id;
		$cart_id = $request->unique_cart_id;

		//first ensure if this cart is pending:
		$queryKeysValues = [
			'unique_cart_id' => $cart_id,
		];
		$cartObject = $this->CartReadSpecificService($queryKeysValues);
		$payment_status = $cartObject->cart_payment_status;
		if($payment_status === 'cleared')
		{
			throw new \Exception('Cannot delete cleared carts!');
		}

		//then start the deletion process:
		$deleteKeysValues = [
			'unique_buyer_id' => $buyer_id,
			'unique_cart_id' => $cart_id,
		];
		$cart_is_deleted = $this->CartDeleteSpecificService($deleteKeysValues);
		return $cart_is_deleted;
	}	

}