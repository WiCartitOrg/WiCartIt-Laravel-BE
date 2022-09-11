<?php

namespace App\Services\Traits\AuthAbstractions\Vendor;

use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Services\Traits\ModelAbstractions\Vendor\VendorAccessAbstraction;

trait VerifyEmailAbstraction 
{
    use VendorAccessAbstraction;
    /**
     * Mark the authenticated vendor's email address as verified.
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    
    protected function VendorConfirmVerifiedStateViaEmail(string $vendor_email) : bool
	{
        $queryKeysValues = ['vendor_email' =>$vendor_email];
		$detailsFoundViaEmail = $this?->VendorReadSpecificService($queryKeysValues);
		//get the verified state:
		$verified_status = $detailsFoundViaEmail['is_email_verified'];

		return $verified_status;
	}

    protected function VendorConfirmVerifiedStateViaId(string $unique_vendor_id): bool
    {
        $queryKeysValues = ['unique_vendor_id' => $unique_vendor_id];
        $detailsFoundViaId = $this?->VendorReadSpecificService($queryKeysValues);
        //get the verified state:
        $verified_status = $detailsFoundViaId['is_email_verified'];

        return $verified_status;
    }

    protected function VendorChangeVerifiedState(string $unique_vendor_id): bool
    {
        $queryKeysValues = ['unique_vendor_id' => $unique_vendor_id];
        $newKeysValues = ['is_email_verified' => true];
		$is_verified = $this?->VendorUpdateSpecificService($queryKeysValues, $newKeysValues);

        return $is_verified;
    }

}
