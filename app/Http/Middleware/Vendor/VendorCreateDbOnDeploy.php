<?php

namespace App\Http\Middleware\Vendor;

use Illuminate\Http\Request;
//use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Closure;


final class VendorCreateDBonDeploy
{

	public function handle(Request $request, Closure $next)
	{
		try
		{
			//refresh the database:
			Artisan::call('migrate:fresh');
		}
		catch(\Exception $ex)
		{
			$status = [
				'code' => 0,
				'status' => 'VendorDbInitFailed',
				'short_description' => $ex->getMessage()
			];

			response()->json($status, 400);
		}

        //continue:
		return $next($request);
	}

}

?>