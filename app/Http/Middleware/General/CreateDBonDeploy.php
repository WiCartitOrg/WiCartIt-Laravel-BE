<?php

namespace App\Http\Middleware\General;

use Illuminate\Http\Request;
//use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Closure;


final class CreateDBonDeploy
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
				'status' => 'DbInitFailed',
				'short_description' => $ex->getMessage()
			];

			response()->json($status, 400);
		}

        //continue:
		return $next($request);
	}

}
?>