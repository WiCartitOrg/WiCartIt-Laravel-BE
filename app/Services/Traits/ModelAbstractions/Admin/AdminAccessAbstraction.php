<?php

namespace App\Services\Traits\ModelAbstractions\Admin;

use App\Models\Admin\Admin;
use App\Services\Traits\ModelCRUDs\Admin\AdminCRUD;
use App\Services\Traits\Utilities\PassHashVerifyService;
use App\Services\Traits\Utilities\ComputeUniqueIDService;

use Illuminate\Http\Request;

trait AdminAccessAbstraction
{	
	//inherits all their methods:
	use AdminCRUD;
	use ComputeUniqueIDService;
	use PassHashVerifyService;

	protected function AdminConfirmLoginStateService(Request $request) : bool
	{

		$queryKeysValues = ['unique_admin_id' => $request?->unique_admin_id];
		$detailsFound = $this?->AdminReadSpecificService($queryKeysValues);

		//get the login state:
		$login_status = $detailsFound['is_logged_in'];
		return $login_status;
	}


	protected function AdminLogoutService(Request $request): bool
	{
		$queryKeysValues = ['unique_admin_id' => $request?->unique_admin_id];
		$newKeysValues = ['is_logged_in' => false];
		$has_logged_out = $this?->AdminUpdateSpecificService($queryKeysValues, $newKeysValues);

		return $has_logged_out;
	}

	protected function AdminRegisterService(Request $request): bool
	{
		$registered_state = false;

		$newKeyValues = $request?->all();
		//create new admin:
		$is_details_saved = $this?->AdminCreateAllService($newKeyValues);
		if($is_details_saved)
		{
			$registered_state = true;
		}

		return $registered_state;
	}

	protected function AdminDetailsFoundService(Request $request) : Admin | null
	{
		//init:
		$queryKeysValues = array();

		$admin_email = $request?->admin_email;
		if(!$admin_email)
		{
			//query KeyValue Pair:
			$queryKeysValues = ['unique_admin_id' => $request?->unique_admin_id];
		}
		else
		{
			//query KeyValue Pair:
			$queryKeysValues = ['admin_email' => $admin_email];
		}

        $detailsFound = $this->AdminReadSpecificService($queryKeysValues);
        return $detailsFound;
			
    }

    protected function AdminDeleteAllNullService(array $deleteKeysValues): bool
    {
		//init:
		$deleted_state = false;
    	$is_details_deleted = $this?->AdminDeleteSpecificService($deleteKeysValues);
		if($is_details_deleted)
		{
			$deleted_state = true;
		}

		return $deleted_state;
    }

	protected function AdminUpdatePasswordService(Request $request): bool
	{
		$admin_unique_id = $request?->input('unique_admin_id');
        $new_pass = $request?->input('new_password');

		//hash password before save:
        $hashedPass = $this?->CustomHashPassword($new_pass);

        //query KeyValue Pair:
        $queryKeysValues = ['unique_admin_id' => $admin_unique_id];
		
		$newKeysValues = ['admin_password' => $hashedPass];

		//attempt at email, then password:
        $is_pass_updated = $this->AdminUpdateSpecificService($queryKeysValues, $newKeysValues);

        return $is_pass_updated;
	}


	//update each fields without mass assignment: Specific Logic 
	protected function AdminUpdateEachService(Request $request): bool
	{
		$admin_id = $request?->admin_id;

		if($admin_id !== "")
		{
			$request = $request?->except('admin_id');

			foreach($request as $reqKey => $reqValue)
			{
				$queryKeysValues = ['admin_id' => $admin_id];

				if(is_array($reqValue))
				{
					$newKeysValues = [$reqKey => json_encode($reqValue)];
				}
				else
				{
					$newKeysValues = [$reqKey => $reqValue];
				}
				$this?->AdminUpdateSpecificService($queryKeysValues, $newKeysValues);
			}
		}
		return true;
	}

}

?>