<?php

namespace App\Http\Middleware\Buyer;

use Illuminate\Http\Request;

use Closure;
use App\Services\Traits\ModelAbstractions\Buyer\BuyerAccessAbstraction;

final class DestroyTokenAfterLogout
{
	use BuyerAccessAbstraction;

	public function handle(Request $request, Closure $next)
	{
		//Before:
		//After:
		//Pass to next stack:
		$response = $next($request);

        //Delete all Auth header token from db after logout:
        //get the user object:
		try
		{
        	$buyerObject = $this?->BuyerDetailsFoundService($request);

			//query params:
			$queryKeysValues = ['tokenable_id' => $buyerObject?->id];
        	//use object to delete token:
        	$buyer_token_was_deleted = $buyerObject?->tokens()?->where($queryKeysValues)?->delete();
			if(!$buyer_token_was_deleted)
			{
				$queryKeysValues = ['unique_buyer_id' => $request?->unique_buyer_id];
				$newKeysValues = [ 'is_logged_in' => true];

				//restore this user back to a logged in user:
				$login_status_was_updated = $this?->BuyerUpdateSpecificService($queryKeysValues, $newKeysValues);
				if($login_status_was_updated)
				{
					throw new \Exception("Failed to Logout: Auth Bearer Token cannot be deleted!");
				}
			}
		}
		catch(\Exception $ex)
		{
			$status = [
				'code' => 0,
				'serverStatus' => 'LogoutFailure!',
				'short_description' => $ex->getMessage()
			];
			return response()->json($status, 400);
		}

        return $response;
	}
	
}