<?php

namespace App\Services\Traits\ModelCRUDs\General;

use App\Models\General\Product;
use App\Models\Vendor\Vendor;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\LazyCollection;

trait ProductCRUD 
{
	//CRUD for services:
	protected function ProductCreateAllService(Request | array $paramsToBeSaved): Product|null
	{
		$is_product_created = Product::create($paramsToBeSaved); 	
		return $is_product_created;		
	}

	protected function ProductCreateAllThroughVendor(Vendor $vendorObject, array $product_array_to_persist): Product|null
	{
		$is_product_created = $vendorObject->products()->create($product_array_to_persist);
		return $is_product_created;
	}

	protected function ProductReadSpecificService(array $queryKeysValues): Product | null 
	{	
		$readModel = Product::where($queryKeysValues)->first();
		return $readModel;
	}

	protected function ProductReadAllThroughVendorService(Vendor $vendorObject): LazyCollection
	{
		$readModel = $vendorObject->products()->lazy();
		return $readModel;
	}


	protected function ProductReadAllService(): array
	{
		$readAllModel = Product::get();
		return $readAllModel;
	}

	protected function ProductReadAllLazyService(): LazyCollection
	{
		$readAllModel = Product::lazy();
		return $readAllModel;
	}


	protected function ProductReadAllLazySpecificService(array $queryKeysValues): LazyCollection
	{
		$readAllModel = Product::where($queryKeysValues)->lazy();
		return $readAllModel;
	}

	protected function ProductReadSpecificAllService(array $queryKeysValues): array 
	{
		$readSpecificAllModel = Product::where($queryKeysValues)->get();
		return $readSpecificAllModel;
	}


	protected function ProductUpdateSpecificService(array $queryKeysValues, array $newKeysValues): bool
	{
		$is_product_updated = Product::where($queryKeysValues)->update($newKeysValues);
		return $is_product_updated;
	}

	protected function ProductDeleteSpecificService(array $deleteKeysValues): bool
	{
		$is_product_deleted = Product::where($deleteKeysValues)->delete();
		return $is_product_deleted;
	}

}

?>