<?php

namespace App\Services\Traits\ModelAbstractions\Buyer;;

use Illuminate\Http\Request;

/*use App\Services\Traits\CommentRateCRUD;
use App\Services\Utilities\ComputeUniqueIDService;*/


trait CommentRateAbstraction
{
	/*use CommentRateCRUD;
	use ComputeUniqueIDService;
	
	protected function BuyerCommentRateService(Request $request): bool
	{
		$buyer_id = $request->buyer_id;
		$comment = $request->comment;
		$rate = $request->rate;
		
		//first generate a unique comment_rate_id:
		$comment_rate_id = $this->genUniqueNumericId();

		$toBeSavedParams = [
			'buyer_id' => $buyer_id,
			'comment_rate_id' => $comment_rate_id,
			'comment' => $comment,
			'rate' => $rate,
			'is_approved_for_view' => false
			//admin has to approve this for view before it can be displayed to other customers
		];

		//now save first in the comment_rate_table:
		$this->CommentRateCreateAllService($toBeSavedParams);
		return true;
	}

	protected function BuyerViewOtherBuyersCommentRateService(Request $request): array
	{
		$buyer_id = $request->buyer_id;

		//$queryParams = [
			//'buyer_id' => $buyer_id,
			//'is_approved_for_view' => false
			//admin has to approve this for view before it can be displayed to other customers
		//];

		//now save first in the comment_rate_table:
		$other_buyers_comments_rates = $this->CommentRateReadAllExceptLazyService($buyer_id);
		return $other_buyers_comments_rates;
	}*/

}

?>