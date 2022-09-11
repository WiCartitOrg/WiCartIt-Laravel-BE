<?php

namespace App\Services\Traits\ModelCRUDs\Admin;

use App\Models\AdminBankDetails;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

trait AdminBankDetailsCRUD 
{
	//CRUD for services:
	protected function  AdminBankDetailsCreateAllService(Request | array $paramsToBeSaved): bool
	{
		AdminBankDetails::create($paramsToBeSaved); 	
		return true;		
	}


	protected function  AdminBankDetailsReadSpecificService(array $queryKeysValues): AdminBankDetails | null //Object
	{	
		$readModel = AdminBankDetails::where($queryKeysValues)->first();
		return $readModel;
	}


	protected function  AdminBankDetailsReadAllService(): Collection
	{
		$readAllModel = AdminBankDetails::get();
		return $readAllModel;
	}

	protected function  AdminBankDetailsReadAllLazyService(): array
	{
		$readAllModel = AdminBankDetails::lazy();
		return $readAllModel;
	}


	protected function  AdminBankDetailsReadAllLazySpecificService(array $queryKeysValues): array
	{
		$readAllModel = AdminBankDetails::where($queryKeysValues)->lazy();
		return $readAllModel;
	}

	protected function  AdminBankDetailsReadSpecificAllService(array $queryKeysValues): array 
	{
		$readSpecificAllModel = AdminBankDetails::where($queryKeysValues)->get();
		return $readAllModel;
	}


	protected function  AdminBankDetailsUpdateSpecificService(array $queryKeysValues, array $newKeysValues): bool
	{
		return AdminBankDetails::where($queryKeysValues)->update($newKeysValues);
	}

	protected function  AdminBankDetailsDeleteSpecificService(array $deleteKeysValues): bool
	{
		return AdminBankDetails::where($deleteKeysValues)->delete();
	}

}

?>