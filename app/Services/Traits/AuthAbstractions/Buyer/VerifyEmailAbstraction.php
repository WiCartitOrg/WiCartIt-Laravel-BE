<?php

namespace App\Services\Traits\AuthAbstractions\Buyer;

use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Services\Traits\ModelAbstractions\Buyer\BuyerAccessAbstraction;

trait VerifyEmailAbstraction 
{
    use BuyerAccessAbstraction;
    /**
     * Mark the authenticated buyer's email address as verified.
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    
    protected function BuyerConfirmVerifiedStateViaEmail(string $buyer_email) : bool
	{
        $queryKeysValues = ['buyer_email' =>$buyer_email];
		$detailsFoundViaEmail = $this?->BuyerReadSpecificService($queryKeysValues);
		//get the verified state:
		$verified_status = $detailsFoundViaEmail['is_email_verified'];

		return $verified_status;
	}

    protected function BuyerConfirmVerifiedStateViaId(string $unique_buyer_id): bool
    {
        $queryKeysValues = ['unique_buyer_id' => $unique_buyer_id];
        $detailsFoundViaId = $this?->BuyerReadSpecificService($queryKeysValues);
        //get the verified state:
        $verified_status = $detailsFoundViaId['is_email_verified'];

        return $verified_status;
    }

    protected function BuyerChangeVerifiedState(string $unique_buyer_id): bool
    {
        $queryKeysValues = ['unique_buyer_id' => $unique_buyer_id];
        $newKeysValues = ['is_email_verified' => true];
		$is_verified = $this?->BuyerUpdateSpecificService($queryKeysValues, $newKeysValues);

        return $is_verified;
    }

}
