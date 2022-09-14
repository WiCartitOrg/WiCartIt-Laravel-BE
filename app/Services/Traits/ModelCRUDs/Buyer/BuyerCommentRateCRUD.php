<?php

namespace App\Services\Traits\ModelCRUDs\Buyer;

use Illuminate\Http\Request;
use Illuminate\Support\LazyCollection;

use App\Models\Buyer\BuyerCommentAndRating;
use Illuminate\Support\Collection;

trait BuyerCommentRateCRUD
{
	//CRUD for services:
	protected function CommentRateCreateAllService(Request | array $paramsToBeSaved): BuyerCommentAndRating | null
	{
		$is_comment_rate_created = BuyerCommentAndRating::create($paramsToBeSaved);
		return $is_comment_rate_created;		
	}


	protected function CommentRateReadSpecificService(array $queryKeysValues): array
	{	
		$readModel = BuyerCommentAndRating::where($queryKeysValues)->first();
		return $readModel;
	}


	/*protected function CommentRateReadAllService(): array 
	{
		$readAllModel = BuyerCommentAndRating::get();
		return $readAllModel;
	}*/

	protected function CommentRateReadAllLazyService(array $queryKeysValues): LazyCollection
	{
		$readAllModel = BuyerCommentAndRating::where($queryKeysValues)->lazy()->orderByDesc('rating');
		return $readAllModel;
	}
	

	protected function CommentRateReadSpecificAllService(array $queryKeysValues): Collection 
	{
		$readSpecificAllModel = BuyerCommentAndRating::where($queryKeysValues)->get();
		return $readSpecificAllModel;
	}

	protected function CommentRateReadAllExceptLazyService(string $buyer_id): LazyCollection
	{
		//admin has to approve this for view before it can be displayed to other customers
		$otherBuyersApprovedCommentRate = 
		BuyerCommentAndRating::where('unique_buyer_id', '!==', $buyer_id)
		->where('is_approved_for_view', '===', true)
		->lazy()->orderByDesc('rating');

		return $otherBuyersApprovedCommentRate;
	}


	protected function CommentRateUpdateSpecificService(array $queryKeysValues, array $newKeysValues): bool 
	{
		BuyerCommentAndRating::where($queryKeysValues)->update($newKeysValues);
		return true;
	}

	protected function CommentRateDeleteSpecificService(array $deleteKeysValues): bool
	{
		BuyerCommentAndRating::where($deleteKeysValues)->delete();
		return true;
	}

}

?>