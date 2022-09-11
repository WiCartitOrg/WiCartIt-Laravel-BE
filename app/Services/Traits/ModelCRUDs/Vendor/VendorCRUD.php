<?php

namespace App\Services\Traits\ModelCRUDs\Vendor;

use App\Models\Vendor\Vendor;
use App\Models\General\Product;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\LazyCollection;

trait VendorCRUD
{
	//CRUD for services:
	protected function VendorCreateAllService(Request | array $paramsToBeSaved): Vendor | null
	{
		$is_vendor_created = Vendor::create($paramsToBeSaved);
		return $is_vendor_created;
	}


	protected function VendorReadSpecificService(array $queryKeysValues): Vendor | null
	{	
		$readModel = Vendor::where($queryKeysValues)->first();
		return $readModel;
	}


	protected function VendorReadSpecificThroughProductService(Product $productObject): LazyCollection
	{
		$readModel = $productObject->vendor;
		return $readModel;
	}


	protected function VendorReadAllService(): Collection
	{	
		$readAllModel = Vendor::get();
		return $readAllModel;
	}


	protected function VendorReadSpecificAllService(array $queryKeysValues): array
	{
		$readSpecificAllModel = Vendor::where($queryKeysValues)->get();
		return $readSpecificAllModel;
	}

	protected function VendorReadAllLazyService(): LazyCollection 
	{
		//load this in chunk to avoid memory hang:
		$readAllModel = Vendor::lazy();
		return $readAllModel;
	}

	protected function VendorReadAllLazySpecificService(array $queryKeysValues): LazyCollection 
	{
		//load this in chunk to avoid memory hang:
		$readAllModel = Vendor::where($queryKeysValues)->lazy();
		return $readAllModel;
	}

	protected function VendorReadSpecificAllTestNullService(string $queryParam): LazyCollection
	{
		$readSpecificAllModel = Vendor::lazy()->where($queryParam, "!==", null);
		return $readSpecificAllModel;
	}


	protected function VendorUpdateSpecificService(array $queryKeysValues, array $newKeysValues): bool
	{
		$is_vendor_updated = Vendor::where($queryKeysValues)->update($newKeysValues);
		return $is_vendor_updated;
	}

	protected function VendorDeleteSpecificService(array $deleteKeysValues): bool
	{
		$is_vendor_deleted = Vendor::where($deleteKeysValues)->delete();
		return $is_vendor_deleted;
	}
}