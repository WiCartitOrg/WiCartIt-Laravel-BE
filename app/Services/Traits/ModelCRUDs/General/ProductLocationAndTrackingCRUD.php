<?php

namespace App\Services\Traits\ModelCRUDs\General;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\LazyCollection;

use App\Models\General\ProductLocationAndTracking;

use Illuminate\Http\Request;

trait ProductLocationAndTrackingCRUD
{
	//CRUD for services:
	protected function ProductLocationAndTrackingCreateAllService(Request | array $paramsToBeSaved): bool
	{ 
		$is_loc_track_created = ProductLocationAndTracking::create($paramsToBeSaved);
		return $is_loc_track_created;		
	}

	protected function ProductLocationAndTrackingReadSpecificService(array $queryKeysValues): ProductLocationAndTracking | null
	{	
		$readModel = ProductLocationAndTracking::where($queryKeysValues)->first();
		return $readModel;
	}


	protected function ProductLocationAndTrackingReadAllLazyService(): LazyCollection
	{
		//load this in chunk to avoid memory load:
		$readAllModel = ProductLocationAndTracking::lazy();
		return $readAllModel;
	}

	protected function ProductLocationAndTrackingReadAllLazySpecificService(array $queryKeysValues): LazyCollection
	{
		$allProductLocationAndTrackingPosted = ProductLocationAndTracking::where($queryKeysValues)->lazy();
		return $allProductLocationAndTrackingPosted;
	}


	protected function ProductLocationAndTrackingReadSpecificAllService(array $queryKeysValues): array 
	{
		$readSpecificAllModel = ProductLocationAndTracking::where($queryKeysValues)->get();
		return $readSpecificAllModel;
	}


	protected function ProductLocationAndTrackingUpdateSpecificService(array $queryKeysValues, array $newKeysValues): bool
	{
		$is_loc_track_updated = ProductLocationAndTracking::where($queryKeysValues)->update($newKeysValues);
		return $is_loc_track_updated;
	}


	protected function ProductLocationAndTrackingDeleteSpecificService(array $deleteKeysValues): bool
	{
		$is_loc_track_deleted = ProductLocationAndTracking::where($deleteKeysValues)->delete();
		return $is_loc_track_deleted;
	}

}

?>