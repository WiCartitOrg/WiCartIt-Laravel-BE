<?php

namespace App\Http\Middleware\General;

use Illuminate\Http\Request;

use Closure;
use App\Services\Traits\ModelAbstractions\Buyer\BuyerWishlistFetchAbstraction;

final class DeleteEmptyWishlists
{
	use BuyerWishlistFetchAbstraction;

	public function handle(Request $request, Closure $next)
	{
		//Before:
		//delete all collections where unique_buyer_id and buyer_password == null;
        $deleteKeysValues = [
            'wishlist_attached_products_ids' => 'null',
            //'wishlist_payment_status' => 'pending',
        ];

		$empty_wishlist_is_deleted = $this?->WishlistDeleteAllNullService($deleteKeysValues);
		if(!$empty_wishlist_is_deleted)
		{
			$deleteKeysValues = [
				'wishlist_attached_products_ids' => [],
				//'wishlist_payment_status' => 'pending',
			];
			$this?->WishlistDeleteAllNullService($deleteKeysValues);
		}
		//After:
		//Pass to next stack:
		$response = $next($request);
        return $response;
	}
	
}

/*private function DeleteEmptyPendingWishlists(Request $request)
	{
		$deleteKeysValues = [
			'unique_wishlist_id' => $request->unique_wishlist_id,
			'wishlist_payment_status' => 'pending'
		];

		$is_wishlist_deleted = $this->WishlistDeleteSpecificService($deleteKeysValues);
		return $is_wishlist_deleted;
	}*/