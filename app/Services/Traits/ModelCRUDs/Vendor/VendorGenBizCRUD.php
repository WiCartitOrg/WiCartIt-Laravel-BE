<?php

namespace App\Services\Traits\ModelCRUDs\Vendor;

use App\Models\Vendor\VendorGeneralBusinessDetails;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

trait VendorGenBizCRUD 
{
	//CRUD for services:
	protected function  VendorGenBizCreateAllService(Request | array $paramsToBeSaved): bool
	{
		$is_created = VendorGeneralBusinessDetails::create($paramsToBeSaved); 	
		return $is_created;		
	}


	protected function  VendorGenBizReadSpecificService(array $queryKeysValues): VendorGeneralBusinessDetails | null//Object 0r null
	{	
		$readModel = VendorGeneralBusinessDetails::where($queryKeysValues)->first();
		return $readModel;
	}


	protected function  VendorGenBizReadAllService(): Collection
	{
		$readAllModel = VendorGeneralBusinessDetails::get();
		return $readAllModel;
	}

	protected function  VendorGenBizReadAllLazyService(): array
	{
		$readAllModel = VendorGeneralBusinessDetails::lazy();
		return $readAllModel;
	}


	protected function  VendorGenBizReadAllLazySpecificService(array $queryKeysValues): array
	{
		$readAllModel = VendorGeneralBusinessDetails::where($queryKeysValues)->lazy();
		return $readAllModel;
	}

	protected function  VendorGenBizReadSpecificAllService(array $queryKeysValues): array 
	{
		$readSpecificAllModel = VendorGeneralBusinessDetails::where($queryKeysValues)->get();
		return $readSpecificAllModel;
	}


	protected function  VendorGenBizUpdateSpecificService(array $queryKeysValues, array $newKeysValues): bool
	{
		$is_updated = VendorGeneralBusinessDetails::where($queryKeysValues)->update($newKeysValues);
        return $is_updated;
    }

	protected function  VendorGenBizDeleteSpecificService(array $deleteKeysValues): bool
	{
		$is_deleted = VendorGeneralBusinessDetails::where($deleteKeysValues)->delete();
        return $is_deleted;
    }

}

?>