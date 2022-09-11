<?php

namespace App\Services\Traits\ModelCRUDs\Buyer;

use App\Models\Buyer\BuyerBilling;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

trait BuyerBillingCRUD 
{
	//CRUD for services:
	protected function  BuyerBillingCreateAllService(Request | array $paramsToBeSaved): bool
	{
		$is_billing_created = BuyerBilling::create($paramsToBeSaved); 	
		return $is_billing_created;		
	}


	protected function  BuyerBillingReadSpecificService(array $queryKeysValues): BuyerBilling | null //Object
	{	
		$readModel = BuyerBilling::where($queryKeysValues)->first();
		return $readModel;
	}


	protected function  BuyerBillingReadAllService(): Collection
	{
		$readAllModel = BuyerBilling::get();
		return $readAllModel;
	}

	protected function  BuyerBillingReadAllLazyService(): array
	{
		$readAllModel = BuyerBilling::lazy();
		return $readAllModel;
	}


	protected function  BuyerBillingReadAllLazySpecificService(array $queryKeysValues): array
	{
		$readAllModel = BuyerBilling::where($queryKeysValues)->lazy();
		return $readAllModel;
	}

	protected function  BuyerBillingReadSpecificAllService(array $queryKeysValues): array 
	{
		$readSpecificAllModel = BuyerBilling::where($queryKeysValues)->get();
		return $readSpecificAllModel;
	}


	protected function  BuyerBillingUpdateSpecificService(array $queryKeysValues, array $newKeysValues): bool
	{
		$is_billing_updated = BuyerBilling::where($queryKeysValues)->update($newKeysValues);
		return $is_billing_updated;
	}

	protected function  BuyerBillingDeleteSpecificService(array $deleteKeysValues): bool
	{
		$is_billing_deleted = BuyerBilling::where($deleteKeysValues)->delete();
		return $is_billing_deleted;
	}

}

?>