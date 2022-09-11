<?php

namespace App\Http\Middleware\Admin;

use Illuminate\Http\Request;

use App\Services\Traits\ModelAbstractions\Admin\AdminAccessAbstraction;

use Closure;

final class AdminConfirmLoginState
{
	use AdminAccessAbstraction;

	public function handle(Request $request, Closure $next)
	{
		/*let's say a user is logged in on a specific device previously...
			Then he used another browser or device to log out, 
			if he comes back to use the application on the former browser, 
            they should be logged out immediately:*/ 
        //Before:
        try
        {
            $logged_in = $this?->AdminConfirmLoginStateService($request);

            if(!$logged_in)
            {
              throw new \Exception("Not logged in yet!");
            }
        }
        catch(\Exception $ex)
        {
            $status = [
                'code' => 0,
                'serverStatus' => 'CurrentlyLoggedOut!',
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