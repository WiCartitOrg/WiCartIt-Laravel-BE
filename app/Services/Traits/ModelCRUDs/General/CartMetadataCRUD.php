<?php

namespace App\Services\Traits\ModelCRUDs\General;

use App\Models\General\CartMetadata;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\LazyCollection;

trait CartMetadataCRUD
{
	//CRUD for services:
	protected function CartMetadataCreateAllService(array $paramsToBeSaved): bool
	{ 
		$is_cart_metadata_created = CartMetadata::create($paramsToBeSaved);
		return $is_cart_metadata_created;		
	}

	protected function CartMetadataReadSpecificService(array $queryKeysValues): CartMetadata | null
	{	
		$readModel = CartMetadata::where($queryKeysValues)->first();
		return $readModel;
	}


	protected function CartMetadataReadAllLazyService(): LazyCollection 
	{
		//load this in chunk to avoid memory hang:
		$readAllModel = CartMetadata::lazy();
		return $readAllModel;
	}

	protected function CartMetadataReadAllLazySpecificService(array $queryKeysValues): LazyCollection
	{
		$allCartMetadataPosted = CartMetadata::where($queryKeysValues)->lazy();
		return $allCartMetadataPosted;
	}


	protected function CartMetadataReadSpecificAllService(array $queryKeysValues): Collection
	{
		$readSpecificAllModel = CartMetadata::where($queryKeysValues)->get();
		return $readSpecificAllModel;
	}


	protected function CartMetadataUpdateSpecificService(array $queryKeysValues, array $newKeysValues): bool
	{
		$is_cartmetadata_updated = CartMetadata::where($queryKeysValues)->update($newKeysValues);
		return $is_cartmetadata_updated;
	}


	protected function CartMetadataDeleteSpecificService(array $deleteKeysValues): bool
	{
		$is_cartmetadata_deleted = CartMetadata::where($deleteKeysValues)->delete();
		return $is_cartmetadata_deleted;
	}

}

?>