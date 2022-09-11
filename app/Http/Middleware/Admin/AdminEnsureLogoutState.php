<?php

namespace App\Http\Middleware\Admin;

use Illuminate\Http\Request;

use App\Services\Traits\ModelAbstractions\Admin\AdminAccessAbstraction;

use Closure;

final class AdminEnsureLogoutState
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

      if($logged_in)
      {
        //log user out:
        $is_logged_out = $this?->AdminLogoutService($request);
        if(!$is_logged_out)
        {
          throw new \Exception('Could not logout admin before password reset!');
        }
      }
    }
    catch(\Exception $ex)
    {
      $status = [
        'code' => 0,
        'serverStatus' => 'LogOutFailure!',
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