<?php

namespace App\Services\Traits\ModelAbstractions\Buyer;;

use Illuminate\Http\Request;

use App\Services\Traits\ModelCRUDs\Buyer\BuyerCommentRateCRUD;
use App\Services\Traits\Utilities\ComputeUniqueIDService;
use Illuminate\Support\LazyCollection;

trait BuyerCommentRateAbstraction
{
	use BuyerCommentRateCRUD;
	use ComputeUniqueIDService;
	
	protected function BuyerCommentRateService(Request $request): bool
	{
		$buyer_id = $request?->unique_buyer_id;
		$comment = $request?->comment;
		$rate = $request?->rating;
		
		//first generate a unique comment_rate_id:
		$comment_rate_id = $this?->genUniqueNumericId();

		$toBeSavedParams = [
			'buyer_id' => $buyer_id,
			'unique_comment_rate_id' => $comment_rate_id,
			'comment' => $comment,
			'rating' => $rate,
			'is_approved_for_view' => false
			//admin has to approve this for view before it can be displayed to other customers
		];

		//now save first in the comment_rate_table:
		$comment_rate_is_created = $this?->CommentRateCreateAllService($toBeSavedParams);
		if(!$comment_rate_is_created)
		{
			return false;
		}

		return true;
	}

	protected function BuyerViewOtherBuyersCommentRateService(Request $request): LazyCollection
	{
		$buyer_id = $request?->unique_buyer_id;

		//$queryParams = [
			//'buyer_id' => $buyer_id,
			//'is_approved_for_view' => false
			//admin has to approve this for view before it can be displayed to other customers
		//];

		$other_buyers_comments_rates = $this?->CommentRateReadAllExceptLazyService($buyer_id);
		return $other_buyers_comments_rates;
	}

}

?>