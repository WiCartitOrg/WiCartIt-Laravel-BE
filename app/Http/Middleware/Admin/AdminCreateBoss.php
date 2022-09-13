<?php

namespace App\Http\Middleware\Admin;

use Illuminate\Http\Request;
//use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Closure;

use App\Services\Traits\Utilities\AdminBossDefaultEntriesService;

final class AdminCreateBoss 
{
	use AdminBossDefaultEntriesService;

	public function handle(Request $request, Closure $next)
	{
		try
		{
			//create Admin Defaults in database:
			$defaults_were_created = $this?->CreateAdminBossDefaults();
			if(!$defaults_were_created)
			{
				//clear the database:
				Artisan::call('migrate:fresh');
				//then throw an exception:
				throw new \Exception("Admin not initialized!");
			}
		}
		catch(\Exception $ex)
		{
			$status = [
				'code' => 0,
				'status' => 'AdminBossInitFailed',
				'short_description' => $ex->getMessage()
			];

			response()->json($status, 400);
		}

		return $next($request);
	}

}
?>