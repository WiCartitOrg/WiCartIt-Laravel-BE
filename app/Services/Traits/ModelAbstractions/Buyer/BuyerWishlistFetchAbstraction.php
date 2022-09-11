<?php

namespace App\Services\Traits\ModelAbstractions\Buyer;;

use Illuminate\Http\Request;
use Illuminate\Support\LazyCollection;
use App\Models\General\Wishlist;

use App\Services\Traits\ModelCRUDs\General\ProductCRUD;
use App\Services\Traits\ModelCRUDs\General\WishlistCRUD;
use App\Services\Traits\ModelCRUDs\Buyer\BuyerCRUD;
use App\Services\Traits\ModelAbstractions\Buyer\BuyerProductAbstraction;

use App\Services\Traits\Utilities\ComputeUniqueIDService;


trait BuyerWishlistFetchAbstraction
{
	//inherits all their methods:
	use ProductCRUD;
	use WishlistCRUD;
	use BuyerCRUD;
	use BuyerProductAbstraction;

	use ComputeUniqueIDService;


	protected function BuyerFetchWishlistsIDsOnlyService(Request $request): array
	{
		//assign:
		$queryKeysValues1 = [
			'unique_buyer_id' => $request->unique_buyer_id,
		];

		$all_wishlists = $this->WishlistReadAllLazySpecificService($queryKeysValues1);

		//get only wishlist ids:
		$all_wishlists_ids = $all_wishlists->pluck('unique_wishlist_id');

		//add to summary:
		$pending_summary['ids'] = $all_wishlists_ids;
		
		return [
			json_encode($pending_summary),
		];
	}


	protected function BuyerFetchEachWishlistDetailByIDService(Request $request) : Wishlist | null
	{	
		//assign:
		$buyer_id = $request->unique_buyer_id; 
		$wishlist_id = $request->unique_wishlist_id;

		$queryKeysValues = [
			'unique_buyer_id' => $buyer_id,
			'unique_wishlist_id' => $wishlist_id,
		];

		$wishlistsObject =  $this->WishlistReadSpecificService($queryKeysValues);

		return $wishlistsObject;
	}
	

	protected function BuyerFetchAllWishlistsDetailsService($request): LazyCollection
	{
		//assign:
		$queryKeysValues1 = [
			'unique_buyer_id' => $request->unique_buyer_id,
		];

		$all_wishlists_details = $this->WishlistReadAllLazySpecificService($queryKeysValues1);
		return $all_wishlists_details;
	}


	protected function BuyerFetchAllWishlistProductsIDsOnlyService(Request $request): LazyCollection
	{
		//assign:
		$buyer_id = $request->unique_buyer_id;
		$wishlist_id = $request->unique_wishlist_id;

		//query:
		$queryKeysValues = [
			'unique_buyer_id' => $buyer_id,
			'unique_wishlist_id' => $wishlist_id,
		];

		//first get all the product_ids associated with this wishlist:
		$thisWishlistDetails =  $this->WishlistReadAllLazyService($queryKeysValues);
		$product_ids = $thisWishlistDetails->pluck('wishlist_attached_products_ids_quantities');//this should return in array(it's now casted)
		//['product_id' => quantities]...
		return	$product_ids;
	}
	

	protected function WishlistDeleteAllNullService(array $deleteKeysValues): bool
    {
		//init:
		$deleted_state = false;
    	$is_details_deleted = $this?->WishlistDeleteSpecificService($deleteKeysValues);
		if($is_details_deleted)
		{
			$deleted_state = true;
		}

		return $deleted_state;
    }


}