<?php

namespace App\Http\Middleware\General;

use Illuminate\Http\Request;

use Closure;
use App\Services\Traits\ModelAbstractions\Buyer\BuyerCartFetchAbstraction;

final class DeleteEmptyCarts
{
	use BuyerCartFetchAbstraction;

	public function handle(Request $request, Closure $next)
	{
		//Before:
		//delete all collections where unique_buyer_id and buyer_password == null;
        $deleteKeysValues = [
            'cart_attached_products_ids' => 'null',
            'cart_payment_status' => 'pending',
        ];

		$empty_cart_is_deleted = $this?->CartDeleteAllNullService($deleteKeysValues);
		if(!$empty_cart_is_deleted)
		{
			$deleteKeysValues = [
				'cart_attached_products_ids' => [],
				'cart_payment_status' => 'pending',
			];
			$this?->CartDeleteAllNullService($deleteKeysValues);
		}
		//After:
		//Pass to next stack:
		$response = $next($request);
        return $response;
	}
	
}

/*private function DeleteEmptyPendingCarts(Request $request)
	{
		$deleteKeysValues = [
			'unique_cart_id' => $request->unique_cart_id,
			'cart_payment_status' => 'pending'
		];

		$is_cart_deleted = $this->CartDeleteSpecificService($deleteKeysValues);
		return $is_cart_deleted;
	}*/