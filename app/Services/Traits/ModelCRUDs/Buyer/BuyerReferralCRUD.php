<?php

namespace App\Services\Traits\ModelCRUDs\Buyer;

use Illuminate\Http\Request;

use App\Models\General\Referral;

trait ReferralCRUD
{
	//CRUD for services:
	protected function ReferralCreateAllService(Request | array $paramsToBeSaved): bool
	{ 
		$is_referral_created = Referral::create($paramsToBeSaved);
		return $is_referral_created;		
	}

	protected function ReferralReadSpecificService(array $queryKeysValues): Referral | null
	{	
		$readModel = Referral::where($queryKeysValues)->first();
		return $readModel;
	}

	protected function ReferralReadAllLazyService(): array 
	{
		//load this in chunk to avoid memory load:
		$readAllModel = Referral::lazy();
		return $readAllModel;
	}

	protected function ReferralReadAllLazySpecificService(array $queryKeysValues): array
	{
		$allReferralPosted = Referral::where($queryKeysValues)->lazy();
		return $allReferralPosted;
	}


	protected function ReferralReadSpecificAllService(array $queryKeysValues): array 
	{
		$readSpecificAllModel = Referral::where($queryKeysValues)->get();
		return $readSpecificAllModel;
	}


	protected function ReferralUpdateSpecificService($queryKeysValues, $newKeysValues)
	{
		$is_referral_updated = Referral::where($queryKeysValues)->update($newKeysValues);
		return $is_referral_updated;
	}

	protected function ReferralDeleteSpecificService($queryKeysValues)
	{
		$is_referral_deleted = Referral::where($queryKeysValues)->delete();
		return $is_referral_deleted;
	}


}