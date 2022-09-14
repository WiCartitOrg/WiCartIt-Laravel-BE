<?php 

namespace App\Services\Traits\ModelAbstractions\Buyer;;

use App\Services\Traits\ModelCRUDs\Buyer\BuyerCRUD;
use App\Services\Traits\ModelCRUDs\General\CartCRUD;
use App\Services\Traits\ModelCRUDs\General\ProductLocationAndTrackingCRUD;

use Illuminate\Http\Request;

trait BuyerExtrasAbstraction
{   
    //inherits all their methods:
    use BuyerCRUD;
    use CartCRUD;
    use ProductLocationAndTrackingCRUD;
    
    protected function BuyerTrackGoodsService(Request $request): array
    {
        $buyer_id = $request?->unique_buyer_id;
        $cart_id = $request?->unique_cart_id;

        $queryKeysValues = [
            'buyer_id' => $buyer_id, 
            'cart_id' => $cart_id,
        ];

        //check this cart status:
        $check_cart_status = $this?->CartReadSpecificService($queryKeysValues)->cart_payment_status;
        if($check_cart_status == 'pending')
        {
            //only cleared products can be tracked:
            return false;
        }

        $location_details = $this?->ProductLocationAndTrackingReadSpecificService($queryKeysValues);
        return $location_details;
        //return from location table values: 
        //present location, expected date and time of delivery
    }


    protected function BuyerConfirmDeliveryService(Request $request): bool
    {
        $queryKeysValues = [ 
            'unique_buyer_id' => $request?->unique_buyer_id,
            'unique_cart_id' => $request?->unique_cart_id
        ];

        $newKeysValues = [
            'is_cart_delivered' => $request?->is_products_delivered,//true in this case
        ];

        $details_has_updated = $this?->LocationUpdateSpecificService($queryKeysValues, $newKeysValues);

        return $details_has_updated;
    }



    protected function BuyerFetchGeneralStatisticsService(Request $request): array
    {
        //first name and last name
        $queryKeysValues = [
            'unique_buyer_id' => $request?->unique_buyer_id
        ];

        $buyerModel = $this?->BuyerReadSpecificService($queryKeysValues);
        $first_name = $buyerModel?->buyer_first_name;
        $last_name = $buyerModel?->buyer_last_name;


        $queryKeysValues = [
            'unique_buyer_id' => $request?->unique_buyer_id,
            'payment_status' => 'pending'
        ];
        //all pending carts of this user:
        $all_pending_carts = $this?->CartReadAllLazySpecificService($queryKeysValues)?->count();

        $queryKeysValues = [
            'unique_buyer_id' => $request?->unique_buyer_id,
            'payment_status' => 'cleared'
        ];

        $cartModel = $this?->CartReadAllLazySpecificService($queryKeysValues);
        //all cleared carts of this user:
        $all_cleared_carts = $cartModel?->count();

        //total transactions so far:
        $total_transaction = $cartModel?->pluck('purchase_price')?->sum();

        //sales volume:
        $purchase_volume_average = ( ($total_transaction/$all_cleared_carts) / $total_transaction ) * 100;

        $all_cleared_cart_ids = $cartModel?->pluck('unique_cart_id');

        //init:
        $all_tracked_goods_count = 0;
        foreach($all_cleared_cart_ids as $each_cart_id)
        {
            $queryKeysValues = ['unique_cart_id' => $each_cart_id];
            $all_tracked_goods = $this?->LocationsAndTracksReadSpecificService($queryKeysValues);
            if($all_tracked_goods)
            {
                $all_tracked_goods_count += 1;
            } 
        }
        
        return [
            'buyer_first_name' => $first_name,
            'buyer_last_name' => $last_name,
            'all_pending_carts' => $all_pending_carts,
            'all_cleared_carts' => $all_cleared_carts,
            'total_transaction' =>  $total_transaction,
            'all_tracked_goods' => $all_tracked_goods_count,
            'purchase_volume_average' => $purchase_volume_average
        ];
    }


   

    /*This is already provided in the CommentRateAbstraction trait 
    protected function BuyerCommentRateService(Request $request): bool
    {
    }*/
    
}