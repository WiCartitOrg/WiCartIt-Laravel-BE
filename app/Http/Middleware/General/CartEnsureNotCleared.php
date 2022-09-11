<?php

namespace App\Http\Middleware\General;

use Illuminate\Http\Request;

use App\Services\Traits\ModelCRUDs\General\CartCRUD;

use Closure;

final class CartEnsureNotCleared
{
	use CartCRUD;

	public function handle(Request $request, Closure $next)
	{
		/*Certain Edit actions cannot be performed on already cleared carts: */ 

    //Before:
    try
    {
      //already cleared carts:
      $queryKeysValues = ['unique_cart_id' => $request->unique_cart_id];
      $cartObject = $this->CartReadSpecificService($queryKeysValues);

      //check payment status:
      $payment_status = $cartObject->cart_payment_status;
      if($payment_status === 'cleared')
      {
        throw new \LogicException('Cannot perform this operation on cleared carts!');
      }
    }
    catch(\LogicException $ex)
    {
      $status = [
        'code' => 0,
        'serverStatus' => 'OperationsFailure!',
        'short_description' => $ex->getMessage(),
      ];

      return response()->json($status, 403);
    }

    //After:
    //Pass to next stack:
    $response = $next($request);

    //Release response to frontend:
    return $response;
	}
	
}