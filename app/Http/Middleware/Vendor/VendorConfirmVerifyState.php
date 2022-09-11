<?php

namespace App\Http\Middleware\Vendor;

use Illuminate\Http\Request;

use App\Services\Traits\AuthAbstractions\Vendor\VerifyEmailAbstraction;

use Closure;

final class VendorConfirmVerifyState
{
	use VerifyEmailAbstraction;

	public function handle(Request $request, Closure $next)
	{
        //init:
        $vendor_was_verified = false;
        
		/**/ 
        //Before:
        $vendor_email = $request?->vendor_email;
        $vendor_unique_id = $request?->unique_vendor_id;
        try
        {
            if(!$vendor_unique_id)
            {
                $vendor_was_verified_using_email = $this?->VendorConfirmVerifiedStateViaEmail($vendor_email);
                if(!$vendor_was_verified_using_email)
                {
                    throw new \Exception("You are not verified yet! Follow the link sent to your mail to activate your account!");
                }
            }

            if(!$vendor_email)
            {
                $vendor_was_verified_using_id = $this?->VendorConfirmVerifiedStateViaId($vendor_unique_id);
                /*if(!$vendor_was_verified_using_id)
                {
                    throw new \Exception("You are not verified yet! Follow the link sent to your mail to activate your account!");
                }*/
            }
           
        }
        catch(\Exception $ex)
        {
            $status = [
                'code' => 0,
                'serverStatus' => 'VerifyFailure!',
                'short_description' => $ex->getMessage(),
                //'state' => $vendor_was_verified_using_id
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