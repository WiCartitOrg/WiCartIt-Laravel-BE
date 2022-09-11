<?php

namespace App\Services\Traits\ModelCRUDs\General;

use Illuminate\Http\Request;

use App\Models\General\PaymentDetail;


trait PaymentDetailCRUD 
{
	//CRUD for services:
	protected function PaymentDetailCreateAllService(Request | array $paramsToBeSaved): bool
	{
		$is_pay_info_created = PaymentDetail::create($paramsToBeSaved); 	
		return $is_pay_info_created;		
	}

	protected function PaymentDetailReadSpecificService(array $queryKeysValues): PaymentDetail | null
	{	
		$readModel = PaymentDetail::where($queryKeysValues)->first();
		return $readModel;
	}


	protected function PaymentDetailReadAllService(): array
	{
		$readAllModel = PaymentDetail::get();
		return $readAllModel;
	}

	protected function PaymentDetailReadAllLazyService(): array
	{
		$readAllModel = PaymentDetail::lazy();
		return $readAllModel;
	}


	protected function PaymentDetailReadAllLazySpecificService(array $queryKeysValues): array
	{
		$readAllModel = PaymentDetail::where($queryKeysValues)->lazy();
		return $readAllModel;
	}

	protected function PaymentDetailReadSpecificAllService(array $queryKeysValues): array 
	{
		$readSpecificAllModel = PaymentDetail::where($queryKeysValues)->get();
		return $readSpecificAllModel;
	}


	protected function PaymentDetailUpdateSpecificService(array $queryKeysValues, array $newKeysValues): bool
	{
		$is_pay_info_updated = PaymentDetail::where($queryKeysValues)->update($newKeysValues);
		return $is_pay_info_updated;
	}

	protected function PaymentDetailDeleteSpecificService(array $deleteKeysValues): bool
	{
		$is_pay_info_deleted = PaymentDetail::where($deleteKeysValues)->delete();
		return $is_pay_info_deleted;
	}

}

?>