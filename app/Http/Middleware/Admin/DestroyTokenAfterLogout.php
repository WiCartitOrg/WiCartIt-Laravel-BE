<?php

namespace App\Http\Middleware\Admin;

use Illuminate\Http\Request;

use Closure;
use App\Services\Traits\ModelAbstractions\Admin\AdminAccessAbstraction;

final class DestroyTokenAfterLogout
{
	use AdminAccessAbstraction;

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
        	$adminObject = $this?->AdminDetailsFoundService($request);

			//query params:
			$queryKeysValues = ['tokenable_id' => $adminObject?->id];
        	//use object to delete token:
        	$admin_token_was_deleted = $adminObject?->tokens()?->where($queryKeysValues)?->delete();
			if(!$admin_token_was_deleted)
			{
				$queryKeysValues = ['unique_admin_id' => $request?->unique_admin_id];
				$newKeysValues = [ 'is_logged_in' => true];

				//restore this user back to a logged in user:
				$login_status_was_updated = $this?->AdminUpdateSpecificService($queryKeysValues, $newKeysValues);
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