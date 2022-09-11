<?php

namespace App\Services\Traits\ModelCRUDs\Admin;

use App\Models\Admin\Admin;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\LazyCollection;

trait AdminCRUD
{
	//CRUD for services:
	protected function AdminCreateAllService(Request | array $paramsToBeSaved): Admin | null
	{
		$is_admin_created = Admin::create($paramsToBeSaved);
		return $is_admin_created;
	}


	protected function AdminReadSpecificService(array $queryKeysValues): Admin | null
	{	
		$readModel = Admin::where($queryKeysValues)->first();
		return $readModel;
	}


	protected function AdminReadAllService(): Collection
	{	
		$readAllModel = Admin::get();
		return $readAllModel;
	}


	protected function AdminReadSpecificAllService(array $queryKeysValues): array
	{
		$readSpecificAllModel = Admin::where($queryKeysValues)->get();
		return $readSpecificAllModel;
	}

	protected function AdminReadAllLazyService(): LazyCollection 
	{
		//load this in chunk to avoid memory hang:
		$readAllModel = Admin::lazy();
		return $readAllModel;
	}

	protected function AdminReadAllLazySpecificService(array $queryKeysValues): LazyCollection 
	{
		//load this in chunk to avoid memory hang:
		$readAllModel = Admin::where($queryKeysValues)->lazy();
		return $readAllModel;
	}

	protected function AdminReadSpecificAllTestNullService(string $queryParam): LazyCollection
	{
		$readSpecificAllModel = Admin::lazy()->where($queryParam, "!==", null);
		return $readSpecificAllModel;
	}


	protected function AdminUpdateSpecificService(array $queryKeysValues, array $newKeysValues): bool
	{
		$is_admin_updated = Admin::where($queryKeysValues)->update($newKeysValues);
		return $is_admin_updated;
	}

	protected function AdminDeleteSpecificService(array $deleteKeysValues): bool
	{
		$is_admin_deleted = Admin::where($deleteKeysValues)->delete();
		return $is_admin_deleted;
	}
}