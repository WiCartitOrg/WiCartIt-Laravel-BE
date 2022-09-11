<?php

namespace App\Http\Middleware\Buyer;

use Illuminate\Http\Request;

use App\Services\Traits\AuthAbstractions\Buyer\VerifyEmailAbstraction;

use Closure;

final class BuyerConfirmVerifyState
{
	use VerifyEmailAbstraction;

	public function handle(Request $request, Closure $next)
	{
        //init:
        $buyer_was_verified = false;
        
		/**/ 
        //Before:
        $buyer_email = $request?->buyer_email;
        $buyer_unique_id = $request?->unique_buyer_id;
        try
        {
            if(!$buyer_unique_id)
            {
                $buyer_was_verified_using_email = $this?->BuyerConfirmVerifiedStateViaEmail($buyer_email);
                if(!$buyer_was_verified_using_email)
                {
                    throw new \Exception("You are not verified yet! Follow the link sent to your mail to activate your account!");
                }
            }

            if(!$buyer_email)
            {
                $buyer_was_verified_using_id = $this?->BuyerConfirmVerifiedStateViaId($buyer_unique_id);
                /*if(!$buyer_was_verified_using_id)
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
                //'state' => $buyer_was_verified_using_id
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