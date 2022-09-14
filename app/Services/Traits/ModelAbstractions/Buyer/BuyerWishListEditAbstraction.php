<?php

namespace App\Services\Traits\ModelAbstractions\Buyer;;

use Illuminate\Http\Request;

use App\Services\Traits\ModelCRUDs\General\ProductCRUD;
use App\Services\Traits\ModelCRUDs\General\WishlistCRUD;
use App\Services\Traits\ModelCRUDs\Buyer\BuyerCRUD;
use App\Services\Traits\ModelAbstractions\Buyer\BuyerProductAbstraction;
use App\Services\Traits\ModelCRUDs\General\CartCRUD;
use App\Services\Traits\Utilities\ComputeUniqueIDService;

trait BuyerWishlistEditAbstraction
{
	//inherits all their methods:
	use ProductCRUD;
	use BuyerCRUD;
	use WishlistCRUD;
	use CartCRUD;
	use ComputeUniqueIDService;


	protected function BuyerAddProductToWishlistService(Request $request) : bool
	{
		//extract the new array:
		$newKeysValues = [
			'unique_buyer_id' => $request?->unique_buyer_id,
			'unique_wishlist_id' => $this?->genUniqueNumericId(),
			'wishlist_products_count' => $request?->wishlist_products_count,
			'wishlist_payment_currency' => $request?->wishlist_payment_currency,
			'wishlist_products_cost' => $request?->wishlist_products_cost,
			'wishlist_shipping_cost' => $request?->wishlist_shipping_cost,
			'wishlist_total_cost' => $request?->wishlist_total_cost,
			//this is currently expected to be in array, cast this into json before saving:
			'wishlist_attached_products_ids_quantities_quantities' => $request?->wishlist_attached_products_ids_quantities_quantities,//format:['id1'=>quantities]
			//'wishlist_payment_status' => $request?->payment_status,// or cleared(defaults to )
			//'created_at'=> $request?->created_at,
			//there's no need for this as eloquent returns "created_at" field by default...
		];

		//save:
		$is_wishlist_created = $this?->WishlistCreateAllService($newKeysValues);
		return $is_wishlist_created;
	}


	protected function BuyerEditProductsOnWishlistService(Request $request): bool
	{
		$buyer_id = $request?->unique_buyer_id;
		$wishlist_id = $request?->unique_wishlist_id;

		$request = $request?->except('unique_buyer_id', 'unique_wishlist_id');

		foreach($request as $reqKey => $reqValue)
		{
			$queryKeysValues = [
				'unique_buyer_id' => $buyer_id,
				'unique_wishlist_id' => $wishlist_id,
				//'wishlist_payment_status' => '',
			];

			if(is_array($reqValue))
			{
				$newKeysValues = [$reqKey => json_encode($reqValue)];
			}
			else
			{
				$newKeysValues = [$reqKey => $reqValue];
			}

			$wishlistWasUpdated = $this?->WishlistUpdateSpecificService($queryKeysValues, $newKeysValues);
			if(!$wishlistWasUpdated)
			{
				return false;
			}
		}

		return true;
	}
	

	protected function BuyerDeleteWishlistService(Request $request): bool
	{
		$buyer_id = $request?->unique_buyer_id;
		$wishlist_id = $request?->unique_wishlist_id;

		//then start the deletion process:
		$deleteKeysValues = [
			'unique_buyer_id' => $buyer_id,
			'unique_wishlist_id' => $wishlist_id,
		];
		$wishlist_was_deleted = $this?->WishlistDeleteSpecificService($deleteKeysValues);
		return $wishlist_was_deleted;
	}	

	
	protected function BuyerConvertWishlistToCartService(Request $request): bool
	{
		$buyer_id = $request?->unique_buyer_id;
		$wishlist_id = $request?->unique_wishlist_id;

		//query Param:
		$queryKeysValues = [
			'unique_buyer_id' => $buyer_id,
			'unique_wishlist_id' => $wishlist_id
		];
		//first read this wishlist details from db:
		$wishlistObject = $this?->WishlistReadSpecificService($queryKeysValues);

		//then map values into the cart queries:
		$cartcreateNewKeysValues = [
			'unique_buyer_id' => $buyer_id,
			'unique_cart_id' => $this->genUniqueNumericId(),
			'cart_products_count' => $wishlistObject->wishlist_products_count,
			'cart_payment_currency' => $wishlistObject->cart_payment_currency,
			'cart_products_cost' => $wishlistObject->cart_products_cost,
			'cart_shipping_cost' => $wishlistObject->cart_shipping_cost,
			'cart_total_cost' => $wishlistObject->cart_total_cost,
			//this is currently expected to be in array, cast this into json before saving:
			'cart_attached_products_ids_quantities_quantities' => $wishlistObject->cart_attached_products_ids_quantities_quantities,//format:['id1'=>quantities]
			'cart_payment_status' => 'pending',//pending or cleared(defaults to pending)
			//'created_at'=> $request->created_at,
			//there's no need for this as eloquent returns "created_at" field by default...
		];

		//create Cart:
		$cart_was_created_from_wishlist = $this?->CartCreateAllService($cartcreateNewKeysValues);

		return $cart_was_created_from_wishlist;
	}
}