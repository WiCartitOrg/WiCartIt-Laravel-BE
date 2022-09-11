<?php

namespace App\Services\Traits\ModelCRUDs\General;

use App\Models\General\Cart;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\LazyCollection;

trait CartCRUD
{
	//CRUD for services:
	protected function CartCreateAllService(array $paramsToBeSaved): bool
	{ 
		$is_cart_created = Cart::create($paramsToBeSaved);
		return $is_cart_created;		
	}

	protected function CartReadSpecificService(array $queryKeysValues): Cart | null
	{	
		$readModel = Cart::where($queryKeysValues)->first();
		return $readModel;
	}


	protected function CartReadAllLazyService(): LazyCollection 
	{
		//load this in chunk to avoid memory hang:
		$readAllModel = Cart::lazy();
		return $readAllModel;
	}

	protected function CartReadAllLazySpecificService(array $queryKeysValues): LazyCollection
	{
		$allCartPosted = Cart::where($queryKeysValues)->lazy();
		return $allCartPosted;
	}


	protected function CartReadSpecificAllService(array $queryKeysValues): Collection
	{
		$readSpecificAllModel = Cart::where($queryKeysValues)->get();
		return $readSpecificAllModel;
	}


	protected function CartUpdateSpecificService(array $queryKeysValues, array $newKeysValues): bool
	{
		$is_cart_updated = Cart::where($queryKeysValues)->update($newKeysValues);
		return $is_cart_updated;
	}


	protected function CartDeleteSpecificService(array $deleteKeysValues): bool
	{
		$is_cart_deleted = Cart::where($deleteKeysValues)->delete();
		return $is_cart_deleted;
	}

}

?>