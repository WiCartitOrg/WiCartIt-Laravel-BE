<?php

namespace App\Services\Traits\ModelCRUDs\Buyer;

use App\Models\Buyer\Buyer;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\LazyCollection;

trait BuyerCRUD
{
	//CRUD for services:
	protected function BuyerCreateAllService(Request | array $paramsToBeSaved): Buyer | null
	{
		$is_buyer_created = Buyer::create($paramsToBeSaved);
		return $is_buyer_created;
	}


	protected function BuyerReadSpecificService(array $queryKeysValues): Buyer | null
	{	
		$readModel = Buyer::where($queryKeysValues)->first();
		return $readModel;
	}


	protected function BuyerReadAllService(): Collection
	{	
		$readAllModel = Buyer::get();
		return $readAllModel;
	}


	protected function BuyerReadSpecificAllService(array $queryKeysValues): array
	{
		$readSpecificAllModel = Buyer::where($queryKeysValues)->get();
		return $readSpecificAllModel;
	}

	protected function BuyerReadAllLazyService(): LazyCollection 
	{
		//load this in chunk to avoid memory hang:
		$readAllModel = Buyer::lazy();
		return $readAllModel;
	}

	protected function BuyerReadAllLazySpecificService(array $queryKeysValues): LazyCollection 
	{
		//load this in chunk to avoid memory hang:
		$readAllModel = Buyer::where($queryKeysValues)->lazy();
		return $readAllModel;
	}

	protected function BuyerReadSpecificAllTestNullService(string $queryParam): LazyCollection
	{
		$readSpecificAllModel = Buyer::lazy()->where($queryParam, "!==", null);
		return $readSpecificAllModel;
	}


	protected function BuyerUpdateSpecificService(array $queryKeysValues, array $newKeysValues): bool
	{
		$is_buyer_updated = Buyer::where($queryKeysValues)->update($newKeysValues);
		return $is_buyer_updated;
	}

	protected function BuyerDeleteSpecificService(array $deleteKeysValues): bool
	{
		$is_buyer_deleted = Buyer::where($deleteKeysValues)->delete();
		return $is_buyer_deleted;
	}
}