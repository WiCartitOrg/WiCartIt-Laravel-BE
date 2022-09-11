<?php

namespace App\Services\Traits\ModelCRUDs\General;

use App\Models\General\Wishlist;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\LazyCollection;

trait WishlistCRUD
{
	//CRUD for services:
	protected function WishlistCreateAllService(array $paramsToBeSaved): bool
	{ 
		$is_wishlist_created = Wishlist::create($paramsToBeSaved);
		return $is_wishlist_created;		
	}

	protected function WishlistReadSpecificService(array $queryKeysValues): Wishlist | null
	{	
		$readModel = Wishlist::where($queryKeysValues)->first();
		return $readModel;
	}


	protected function WishlistReadAllLazyService(): LazyCollection 
	{
		//load this in chunk to avoid memory hang:
		$readAllModel = Wishlist::lazy();
		return $readAllModel;
	}

	protected function WishlistReadAllLazySpecificService(array $queryKeysValues): LazyCollection
	{
		$allWishlistPosted = Wishlist::where($queryKeysValues)->lazy();
		return $allWishlistPosted;
	}


	protected function WishlistReadSpecificAllService(array $queryKeysValues): Collection
	{
		$readSpecificAllModel = Wishlist::where($queryKeysValues)->get();
		return $readSpecificAllModel;
	}


	protected function WishlistUpdateSpecificService(array $queryKeysValues, array $newKeysValues): bool
	{
		$is_wishlist_updated = Wishlist::where($queryKeysValues)->update($newKeysValues);
		return $is_wishlist_updated;
	}


	protected function WishlistDeleteSpecificService(array $deleteKeysValues): bool
	{
		$is_wishlist_deleted = Wishlist::where($deleteKeysValues)->delete();
		return $is_wishlist_deleted;
	}

}

?>