<?php

namespace App\Services\Traits\ModelCRUDs\General;

use App\Models\Wallet;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\LazyCollection;

trait WalletCRUD 
{
	//CRUD for services:
	protected function WalletCreateAllService(Request | array $paramsToBeSaved): bool
	{
		$is_wallet_created = Wallet::create($paramsToBeSaved); 	
		return $is_wallet_created;		
	}


	protected function WalletReadSpecificService(array $queryKeysValues): Wallet | null 
	{	
		$readModel = Wallet::where($queryKeysValues)->first();
		return $readModel;
	}


	protected function WalletReadAllService(): array
	{
		$readAllModel = Wallet::get();
		return $readAllModel;
	}

	protected function WalletReadAllLazyService(): LazyCollection
	{
		$readAllModel = Wallet::lazy();
		return $readAllModel;
	}


	protected function WalletReadAllLazySpecificService(array $queryKeysValues): LazyCollection
	{
		$readAllModel = Wallet::where($queryKeysValues)->lazy();
		return $readAllModel;
	}

	protected function WalletReadSpecificAllService(array $queryKeysValues): array 
	{
		$readSpecificAllModel = Wallet::where($queryKeysValues)->get();
		return $readSpecificAllModel;
	}


	protected function WalletUpdateSpecificService(array $queryKeysValues, array $newKeysValues): bool
	{
		$is_wallet_updated = Wallet::where($queryKeysValues)->update($newKeysValues);
		return $is_wallet_updated;
	}

	protected function WalletDeleteSpecificService(array $deleteKeysValues): bool
	{
		$is_wallet_deleted = Wallet::where($deleteKeysValues)->delete();
		return $is_wallet_deleted;
	}

}

?>