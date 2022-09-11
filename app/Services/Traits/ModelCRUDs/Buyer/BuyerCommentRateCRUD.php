<?php

namespace App\Services\Traits\ModelCRUDs\Buyer;

use App\Models\CommentRate;

use Illuminate\Http\Request;

trait CommentRateCRUD
{
	//CRUD for services:
	protected function CommentRateCreateAllService(Request | array $paramsToBeSaved):bool
	{
		CommentRate::create($anyParams);
		return true;		
	}


	protected function CommentRateReadSpecificService(array $queryKeysValues): array
	{	
		$readModel = CommentRate::where($queryKeysValues)->first();
		return $readModel;
	}


	/*protected function CommentRateReadAllService(): array 
	{
		$readAllModel = CommentRate::get();
		return $readAllModel;
	}*/

	protected function CommentRateReadAllLazyService(array $queryKeysValues): array 
	{
		$readAllModel = CommentRate::where($queryKeysValues)->lazy()->orderByDesc('rating');
		return $readAllModel;
	}
	

	protected function CommentRateReadSpecificAllService(array $queryKeysValues): array 
	{
		$readSpecificAllModel = CommentRate::where($queryKeysValues)->get();
		return $readAllModel;
	}

	protected function CommentRateReadAllExceptLazyService(string $buyer_id): array
	{
		//admin has to approve this for view before it can be displayed to other customers
		$otherBuyersApprovedCommentRate = 
		CommentRate::where('buyer_id', '!==', $buyer_id)
		->where('is_approved_for_view', '===', true)
		->lazy();

		return $otherBuyersApprovedCommentRate;
	}


	protected function CommentRateUpdateSpecificService(array $queryKeysValues, array $newKeysValues): bool 
	{
		CommentRate::where($queryKeysValues)->update($newKeysValues);
		return true;
	}

	protected function CommentRateDeleteSpecificService(array $deleteKeysValues): bool
	{
		CommentRate::where($deleteKeysValues)->delete();
		return true;
	}

}

?>