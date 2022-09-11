<?php

namespace App\Services\Traits\ModelAbstractions\Buyer;;

use Illuminate\Http\Request;

use App\Services\Traits\ModelCRUDs\General\ProductCRUD;
use App\Services\Traits\ModelCRUDs\General\CartCRUD;
use App\Services\Traits\ModelCRUDs\Buyer\BuyerCRUD;
use App\Services\Traits\ModelAbstractions\Buyer\BuyerProductAbstraction;

use App\Services\Traits\Utilities\ComputeUniqueIDService;
use Illuminate\Support\LazyCollection;

trait BuyerCartFetchAbstraction
{
	//inherits all their methods:
	use ProductCRUD;
	use CartCRUD;
	use BuyerCRUD;
	use BuyerProductAbstraction;

	use ComputeUniqueIDService;

	protected function BuyerFetchCartByCategoryService(Request $request) : array
	{	
		//init:
		$cart_by_category_summary = array();
		//assign:
		$buyer_id = $request->unique_buyer_id; 
		$payment_status = $request->cart_payment_status;

		$queryKeysValues = [
			'unique_buyer_id' => $buyer_id,
			'cart_payment_status' => $payment_status,
		];

		$all_carts_found =  $this->CartReadAllLazySpecificService($queryKeysValues);

		//set the status:
		if($payment_status === 'pending')
		{
			$cart_by_category_summary['status'] = 'pending';
		}
		else if($payment_status === 'cleared')
		{
			$cart_by_category_summary['status'] = 'cleared';
		}

		//set the number of carts found:
		$cart_by_category_summary['carts_total'] = $all_carts_found->count();

		//set all the results from db:
		$cart_by_category_summary['carts_summary'] = $all_carts_found;

		return $cart_by_category_summary;
	}


	protected function BuyerFetchCartsIDsOnlyService(Request $request): array
	{
		//init:
		$pending_summary = [
			'category' => 'pending_ids',
		];

		$cleared_summary = [
			'category' => 'cleared_ids',
		];

		//assign:
		$queryKeysValues1 = [
			'unique_buyer_id' => $request->unique_buyer_id,
			'cart_payment_status' => 'pending'
		];

		$queryKeysValues2 = [
			'unique_buyer_id' => $request->unique_buyer_id,
			'cart_payment_status' => 'cleared'
		];

		$all_pending_carts = $this->CartReadAllLazySpecificService($queryKeysValues1);
		$all_cleared_carts = $this->CartReadAllLazySpecificService($queryKeysValues2);

		//get only cart ids:
		$all_pending_carts_ids = $all_pending_carts->pluck('unique_cart_id');
		$all_cleared_cart_ids = $all_cleared_carts->pluck('unique_cart_id');

		//add to summary:
		$pending_summary['ids'] = $all_pending_carts_ids;
		$cleared_summary['ids'] = $all_cleared_cart_ids;
		
		return [
			json_encode($pending_summary),
			json_encode($cleared_summary),
		];
	}



	protected function BuyerFetchAllCartProductsIDsOnlyService(Request $request): LazyCollection
	{
		//assign:
		$buyer_id = $request->unique_buyer_id;
		$cart_id = $request->unique_cart_id;

		//query:
		$queryKeysValues = [
			'unique_buyer_id' => $buyer_id,
			'unique_cart_id' => $cart_id,
		];

		//first get all the product_ids associated with this cart:
		$thisCartDetails =  $this->CartReadAllLazyService($queryKeysValues);
		$product_ids = $thisCartDetails->pluck('cart_attached_products_ids_quantities_quantities');//this should return in array(it's now casted)

		return	$product_ids;
	}

	protected function CartDeleteAllNullService(array $deleteKeysValues): bool
    {
		//init:
		$deleted_state = false;
    	$is_details_deleted = $this?->CartDeleteSpecificService($deleteKeysValues);
		if($is_details_deleted)
		{
			$deleted_state = true;
		}

		return $deleted_state;
    }


}