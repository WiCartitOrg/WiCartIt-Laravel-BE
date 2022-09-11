<?php

namespace App\Http\Middleware\Buyer;

use Illuminate\Http\Request;

use Closure;
use App\Services\Traits\ModelAbstractions\Buyer\BuyerAccessAbstraction;

final class BuyerDeleteAllNull
{
	use BuyerAccessAbstraction;

	public function handle(Request $request, Closure $next)
	{
		//Before:
		//delete all collections where unique_buyer_id and buyer_password == null;
        $deleteKeysValues = [
            'unique_buyer_id' => 'null',
            'buyer_password' => 'null'
        ];

		$this?->BuyerDeleteAllNullService($deleteKeysValues);

		//After:
		//Pass to next stack:
		$response = $next($request);
        return $response;
	}
	
}