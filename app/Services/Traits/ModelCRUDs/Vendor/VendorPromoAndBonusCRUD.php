<?php

namespace App\Services\Traits\ModelCRUDs\Vendor;

use Illuminate\Http\Request;

use App\Models\PromoAndBonus;

trait PromoAndBonusCRUD
{
	//CRUD for services:
	protected function PromoAndBonusCreateAllService(Request | array $paramsToBeSaved): bool
	{ 
		$is_promo_bonus_created = PromoAndBonus::create($paramsToBeSaved);
		return $is_promo_bonus_created;		
	}

	protected function PromoAndBonusReadSpecificService(array $queryKeysValues): PromoAndBonus | null
	{	
		$readModel = PromoAndBonus::where($queryKeysValues)->first();
		return $readModel;
	}

	protected function PromoAndBonusReadAllLazyService(): array 
	{
		//load this in chunk to avoid memory load:
		$readAllModel = PromoAndBonus::lazy();
		return $readAllModel;
	}

	protected function PromoAndBonusReadAllLazySpecificService(array $queryKeysValues): array
	{
		$allPromoAndBonusPosted = PromoAndBonus::where($queryKeysValues)->lazy();
		return $allPromoAndBonusPosted;
	}


	protected function PromoAndBonusReadSpecificAllService(array $queryKeysValues): array 
	{
		$readSpecificAllModel = PromoAndBonus::where($queryKeysValues)->get();
		return $readSpecificAllModel;
	}


	protected function PromoAndBonusUpdateSpecificService($queryKeysValues, $newKeysValues)
	{
		$is_promo_bonus_updated = PromoAndBonus::where($queryKeysValues)->update($newKeysValues);
		return $is_promo_bonus_updated;
	}

	protected function PromoAndBonusDeleteSpecificService($queryKeysValues)
	{
		$is_promo_bonus_deleted = PromoAndBonus::where($queryKeysValues)->delete();
		return $is_promo_bonus_deleted;
	}


}