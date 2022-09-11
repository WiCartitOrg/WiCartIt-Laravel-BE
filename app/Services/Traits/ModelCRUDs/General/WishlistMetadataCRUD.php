<?php

namespace App\Services\Traits\ModelCRUDs\General;

use App\Models\General\WishlistMetadata;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\LazyCollection;

trait WishlistMetadataCRUD
{
	//CRUD for services:
	protected function WishlistMetadataCreateAllService(array $paramsToBeSaved): bool
	{ 
		$is_wishlist_metadata_created = WishlistMetadata::create($paramsToBeSaved);
		return $is_wishlist_metadata_created;		
	}

	protected function WishlistMetadataReadSpecificService(array $queryKeysValues): WishlistMetadata | null
	{	
		$readModel = WishlistMetadata::where($queryKeysValues)->first();
		return $readModel;
	}


	protected function WishlistMetadataReadAllLazyService(): LazyCollection 
	{
		//load this in chunk to avoid memory hang:
		$readAllModel = WishlistMetadata::lazy();
		return $readAllModel;
	}

	protected function WishlistMetadataReadAllLazySpecificService(array $queryKeysValues): LazyCollection
	{
		$allWishlistMetadataPosted = WishlistMetadata::where($queryKeysValues)->lazy();
		return $allWishlistMetadataPosted;
	}


	protected function WishlistMetadataReadSpecificAllService(array $queryKeysValues): Collection
	{
		$readSpecificAllModel = WishlistMetadata::where($queryKeysValues)->get();
		return $readSpecificAllModel;
	}


	protected function WishlistMetadataUpdateSpecificService(array $queryKeysValues, array $newKeysValues): bool
	{
		$is_wishlistmetadata_updated = WishlistMetadata::where($queryKeysValues)->update($newKeysValues);
		return $is_wishlistmetadata_updated;
	}


	protected function WishlistMetadataDeleteSpecificService(array $deleteKeysValues): bool
	{
		$is_wishlistmetadata_deleted = WishlistMetadata::where($deleteKeysValues)->delete();
		return $is_wishlistmetadata_deleted;
	}

}

?>