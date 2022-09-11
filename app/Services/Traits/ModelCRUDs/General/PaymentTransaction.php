<?php

namespace App\Services\Traits\ModelCRUDs\General;

use App\Models\PaymentTransaction;

use Illuminate\Http\Request;

trait PaymentTransactionCRUD 
{
	//CRUD for services:
	protected function PaymentTransactionCreateAllService(Request | array $paramsToBeSaved): bool
	{
		$is_pay_trans_created = PaymentTransaction::create($paramsToBeSaved); 	
		return $is_pay_trans_created;		
	}

	protected function PaymentTransactionReadSpecificService(array $queryKeysValues): array 
	{	
		$readModel = PaymentTransaction::where($queryKeysValues)->first();
		return $readModel;
	}


	protected function PaymentTransactionReadAllService(): array
	{
		$readAllModel = PaymentTransaction::get();
		return $readAllModel;
	}

	protected function PaymentTransactionReadAllLazyService(): array
	{
		$readAllModel = PaymentTransaction::lazy();
		return $readAllModel;
	}


	protected function PaymentTransactionReadAllLazySpecificService(array $queryKeysValues): array
	{
		$readAllModel = PaymentTransaction::where($queryKeysValues)->lazy();
		return $readAllModel;
	}

	protected function PaymentTransactionReadSpecificAllService(array $queryKeysValues): array 
	{
		$readSpecificAllModel = PaymentTransaction::where($queryKeysValues)->get();
		return $readSpecificAllModel;
	}


	protected function PaymentTransactionUpdateSpecificService(array $queryKeysValues, array $newKeysValues): bool
	{
		$is_pay_trans_updated = PaymentTransaction::where($queryKeysValues)->update($newKeysValues);
		return $is_pay_trans_updated;
	}

	protected function PaymentTransactionDeleteSpecificService(array $deleteKeysValues): bool
	{
		$is_pay_trans_deleted = PaymentTransaction::where($deleteKeysValues)->delete();
		return $is_pay_trans_deleted;
	}

}

?>