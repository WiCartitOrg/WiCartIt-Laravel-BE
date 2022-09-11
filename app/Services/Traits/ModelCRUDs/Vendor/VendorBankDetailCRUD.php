<?php

namespace App\Services\Traits\ModelCRUDs\Vendor;

use App\Models\VendorBankDetail;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\LazyCollection;

trait VendorBankDetailCRUD 
{
	//CRUD for services:
	protected function VendorBankDetailCreateAllService(Request | array $paramsToBeSaved): bool
	{
		$is_bank_detail_created = VendorBankDetail::create($paramsToBeSaved); 	
		return $is_bank_detail_created;		
	}


	protected function VendorBankDetailReadSpecificService(array $queryKeysValues): VendorBankDetail | null 
	{	
		$readModel = VendorBankDetail::where($queryKeysValues)->first();
		return $readModel;
	}


	protected function VendorBankDetailReadAllService(): array
	{
		$readAllModel = VendorBankDetail::get();
		return $readAllModel;
	}

	protected function VendorBankDetailReadAllLazyService(): LazyCollection
	{
		$readAllModel = VendorBankDetail::lazy();
		return $readAllModel;
	}


	protected function VendorBankDetailReadAllLazySpecificService(array $queryKeysValues): LazyCollection
	{
		$readAllModel = VendorBankDetail::where($queryKeysValues)->lazy();
		return $readAllModel;
	}

	protected function VendorBankDetailReadSpecificAllService(array $queryKeysValues): array 
	{
		$readSpecificAllModel = VendorBankDetail::where($queryKeysValues)->get();
		return $readSpecificAllModel;
	}


	protected function VendorBankDetailUpdateSpecificService(array $queryKeysValues, array $newKeysValues): bool
	{
		$is_bank_detail_updated = VendorBankDetail::where($queryKeysValues)->update($newKeysValues);
		return $is_bank_detail_updated;
	}

	protected function VendorBankDetailDeleteSpecificService(array $deleteKeysValues): bool
	{
		$is_bank_detail_deleted = VendorBankDetail::where($deleteKeysValues)->delete();
		return $is_bank_detail_deleted;
	}

}

?>