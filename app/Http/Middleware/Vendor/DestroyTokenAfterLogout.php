<?php

namespace App\Http\Middleware\Vendor;

use Illuminate\Http\Request;

use Closure;
use App\Services\Traits\ModelAbstractions\Vendor\VendorAccessAbstraction;

final class DestroyTokenAfterLogout
{
	use VendorAccessAbstraction;

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
        	$vendorObject = $this?->VendorDetailsFoundService($request);

			//query params:
			$queryKeysValues = ['tokenable_id' => $vendorObject?->id];
        	//use object to delete token:
        	$vendor_token_was_deleted = $vendorObject?->tokens()?->where($queryKeysValues)?->delete();
			if(!$vendor_token_was_deleted)
			{
				$queryKeysValues = ['unique_vendor_id' => $request?->unique_vendor_id];
				$newKeysValues = [ 'is_logged_in' => true];

				//restore this user back to a logged in user:
				$login_status_was_updated = $this?->VendorUpdateSpecificService($queryKeysValues, $newKeysValues);
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