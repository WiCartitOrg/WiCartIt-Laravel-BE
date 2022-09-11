<?php

namespace App\Services\Traits\ModelCRUDs\Buyer;

use App\Models\Buyer\BuyerShipping;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

trait BuyerShippingCRUD 
{
	//CRUD for services:
	protected function  BuyerShippingCreateAllService(Request | array $paramsToBeSaved): bool
	{
		$is_shipping_created = BuyerShipping::create($paramsToBeSaved); 	
		return $is_shipping_created;		
	}


	protected function  BuyerShippingInfoReadSpecificService(array $queryKeysValues): BuyerShipping | null //Object
	{	
		$readModel = BuyerShipping::where($queryKeysValues)->first();
		return $readModel;
	}


	protected function  BuyerShippingReadAllService(): Collection
	{
		$readAllModel = BuyerShipping::get();
		return $readAllModel;
	}

	protected function  BuyerShippingReadAllLazyService(): array
	{
		$readAllModel = BuyerShipping::lazy();
		return $readAllModel;
	}


	protected function  BuyerShippingReadAllLazySpecificService(array $queryKeysValues): array
	{
		$readAllModel = BuyerShipping::where($queryKeysValues)->lazy();
		return $readAllModel;
	}

	protected function  BuyerShippingReadSpecificAllService(array $queryKeysValues): array 
	{
		$readSpecificAllModel = BuyerShipping::where($queryKeysValues)->get();
		return $readSpecificAllModel;
	}


	protected function  BuyerShippingUpdateSpecificService(array $queryKeysValues, array $newKeysValues): bool
	{
		$is_shipping_updated = BuyerShipping::where($queryKeysValues)->update($newKeysValues);
		return $is_shipping_updated;
	}

	protected function  BuyerShippingDeleteSpecificService(array $deleteKeysValues): bool
	{
		$is_shipping_deleted = BuyerShipping::where($deleteKeysValues)->delete();
		return $is_shipping_deleted;
	}

}

?>