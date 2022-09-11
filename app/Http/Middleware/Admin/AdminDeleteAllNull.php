<?php

namespace App\Http\Middleware\Admin;

use Illuminate\Http\Request;

use Closure;
use App\Services\Traits\ModelAbstractions\Admin\AdminAccessAbstraction;

final class AdminDeleteAllNull
{
	use AdminAccessAbstraction;

	public function handle(Request $request, Closure $next)
	{
		//Before:
		//delete all collections where unique_admin_id and admin_password == null;
        $deleteKeysValues = [
            'unique_admin_id' => 'null',
            'admin_password' => 'null'
        ];

		$this?->AdminDeleteAllNullService($deleteKeysValues);

		//After:
		//Pass to next stack:
		$response = $next($request);
        return $response;
	}
	
}