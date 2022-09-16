<?php

namespace App\Services\Traits\ModelAbstractions\Vendor;

use App\Models\Vendor\Vendor;
use App\Services\Traits\ModelCRUDs\Vendor\VendorCRUD;
use App\Services\Traits\Utilities\PassHashVerifyService;
use App\Services\Traits\Utilities\ComputeUniqueIDService;

use Illuminate\Http\Request;

trait VendorAccessAbstraction
{	
	//inherits all their methods:
	use VendorCRUD;
	use ComputeUniqueIDService;
	use PassHashVerifyService;

	protected function VendorConfirmLoginStateService(Request $request) : bool
	{

		$queryKeysValues = ['unique_vendor_id' => $request?->unique_vendor_id];
		$detailsFound = $this?->VendorReadSpecificService($queryKeysValues);

		//get the login state:
		$login_status = $detailsFound['is_logged_in'];
		return $login_status;
	}


	protected function VendorLogoutService(Request $request): bool
	{
		$queryKeysValues = ['unique_vendor_id' => $request?->unique_vendor_id];
		$newKeysValues = ['is_logged_in' => false];
		$has_logged_out = $this?->VendorUpdateSpecificService($queryKeysValues, $newKeysValues);

		return $has_logged_out;
	}

	protected function VendorRegisterService(Request $request): bool
	{
		$registered_state = false;

		$newKeyValues = $request?->all();
		//create new vendor:
		$is_details_saved = $this?->VendorCreateAllService($newKeyValues);
		if($is_details_saved)
		{
			$registered_state = true;
		}

		return $registered_state;
	}

	protected function VendorDetailsFoundService(Request $request) : Vendor | null
	{
		//init:
		$queryKeysValues = array();

		$vendor_email = $request?->vendor_email;
		if(!$vendor_email)
		{
			//query KeyValue Pair:
			$queryKeysValues = ['unique_vendor_id' => $request?->unique_vendor_id];
		}
		else
		{
			//query KeyValue Pair:
			$queryKeysValues = ['vendor_email' => $vendor_email];
		}

        $detailsFound = $this?->VendorReadSpecificService($queryKeysValues);
        return $detailsFound;
			
    }

    protected function VendorDeleteAllNullService(array $deleteKeysValues): bool
    {
		//init:
		$deleted_state = false;
    	$is_details_deleted = $this?->VendorDeleteSpecificService($deleteKeysValues);
		if($is_details_deleted)
		{
			$deleted_state = true;
		}

		return $deleted_state;
    }

	protected function VendorUpdatePasswordService(Request $request): bool
	{
		$vendor_unique_id = $request?->input('unique_vendor_id');
        $new_pass = $request?->input('new_password');

		//hash password before save:
        $hashedPass = $this?->CustomHashPassword($new_pass);

        //query KeyValue Pair:
        $queryKeysValues = ['unique_vendor_id' => $vendor_unique_id];
		
		$newKeysValues = ['vendor_password' => $hashedPass];

		//attempt at email, then password:
        $is_pass_updated = $this?->VendorUpdateSpecificService($queryKeysValues, $newKeysValues);

        return $is_pass_updated;
	}


	//update each fields without mass assignment: Specific Logic 
	protected function VendorUpdateEachService(Request $request): bool
	{
		$vendor_id = $request?->vendor_id;

		if($vendor_id !== "")
		{
			$request = $request?->except('vendor_id');

			foreach($request as $reqKey => $reqValue)
			{
				$queryKeysValues = ['vendor_id' => $vendor_id];

				if(is_array($reqValue))
				{
					$newKeysValues = [$reqKey => json_encode($reqValue)];
				}
				else
				{
					$newKeysValues = [$reqKey => $reqValue];
				}
				$this?->VendorUpdateSpecificService($queryKeysValues, $newKeysValues);
			}
		}
		return true;
	}

}

?>