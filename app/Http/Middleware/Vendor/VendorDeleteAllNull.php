<?php

namespace App\Http\Middleware\Vendor;

use Illuminate\Http\Request;

use Closure;
use App\Services\Traits\ModelAbstractions\Vendor\VendorAccessAbstraction;

final class VendorDeleteAllNull
{
	use VendorAccessAbstraction;

	public function handle(Request $request, Closure $next)
	{
		//Before:
		//delete all collections where unique_vendor_id and vendor_password == null;
        $deleteKeysValues = [
            'unique_vendor_id' => 'null',
            'vendor_password' => 'null'
        ];

		$this?->VendorDeleteAllNullService($deleteKeysValues);

		//After:
		//Pass to next stack:
		$response = $next($request);
        return $response;
	}
	
}