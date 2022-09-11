<?php

namespace App\Services\Traits\ModelAbstractions\Buyer;

use App\Models\Buyer\Buyer;
use App\Services\Traits\ModelCRUDs\Buyer\BuyerCRUD;
use App\Services\Traits\Utilities\PassHashVerifyService;
use App\Services\Traits\Utilities\ComputeUniqueIDService;

use Illuminate\Http\Request;

trait BuyerAccessAbstraction
{	
	//inherits all their methods:
	use BuyerCRUD;
	use ComputeUniqueIDService;
	use PassHashVerifyService;

	protected function BuyerConfirmLoginStateService(Request $request) : bool
	{

		$queryKeysValues = ['unique_buyer_id' => $request?->unique_buyer_id];
		$detailsFound = $this?->BuyerReadSpecificService($queryKeysValues);

		//get the login state:
		$login_status = $detailsFound['is_logged_in'];
		return $login_status;
	}


	protected function BuyerLogoutService(Request $request): bool
	{
		$queryKeysValues = ['unique_buyer_id' => $request?->unique_buyer_id];
		$newKeysValues = ['is_logged_in' => false];
		$has_logged_out = $this?->BuyerUpdateSpecificService($queryKeysValues, $newKeysValues);

		return $has_logged_out;
	}

	protected function BuyerRegisterService(Request $request): bool
	{
		$registered_state = false;

		$newKeyValues = $request?->all();
		//create new buyer:
		$is_details_saved = $this?->BuyerCreateAllService($newKeyValues);
		if($is_details_saved)
		{
			$registered_state = true;
		}

		return $registered_state;
	}

	protected function BuyerDetailsFoundService(Request $request) : Buyer | null
	{
		//init:
		$queryKeysValues = array();

		$buyer_email = $request?->buyer_email;
		if(!$buyer_email)
		{
			//query KeyValue Pair:
			$queryKeysValues = ['unique_buyer_id' => $request?->unique_buyer_id];
		}
		else
		{
			//query KeyValue Pair:
			$queryKeysValues = ['buyer_email' => $buyer_email];
		}

        $detailsFound = $this->BuyerReadSpecificService($queryKeysValues);
        return $detailsFound;
			
    }

    protected function BuyerDeleteAllNullService(array $deleteKeysValues): bool
    {
		//init:
		$deleted_state = false;
    	$is_details_deleted = $this?->BuyerDeleteSpecificService($deleteKeysValues);
		if($is_details_deleted)
		{
			$deleted_state = true;
		}

		return $deleted_state;
    }

	protected function BuyerUpdatePasswordService(Request $request): bool
	{
		$buyer_unique_id = $request?->input('unique_buyer_id');
        $new_pass = $request?->input('new_password');

		//hash password before save:
        $hashedPass = $this?->CustomHashPassword($new_pass);

        //query KeyValue Pair:
        $queryKeysValues = ['unique_buyer_id' => $buyer_unique_id];
		
		$newKeysValues = ['buyer_password' => $hashedPass];

		//attempt at email, then password:
        $is_pass_updated = $this->BuyerUpdateSpecificService($queryKeysValues, $newKeysValues);

        return $is_pass_updated;
	}


	//update each fields without mass assignment: Specific Logic 
	protected function BuyerUpdateEachService(Request $request): bool
	{
		$buyer_id = $request?->buyer_id;

		if($buyer_id !== ""){

			$request = $request?->except('buyer_id');

			foreach($request as $reqKey => $reqValue){

				$queryKeysValues = ['buyer_id' => $buyer_id];

				if(is_array($reqValue))
				{
					$newKeysValues = [$reqKey => json_encode($reqValue)];
				}else{
					$newKeysValues = [$reqKey => $reqValue];
				}
				$this?->BuyerUpdateSpecificService($queryKeysValues, $newKeysValues);
			}
		}
		return true;
	}

}

?>