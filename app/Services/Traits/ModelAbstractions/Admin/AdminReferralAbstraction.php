<?php 

namespace App\Services\Traits\ModelAbstractions\Admin;

use Illuminate\Http\Request;


use App\Services\Traits\ModelCRUDs\Admin\AdminCRUD;
use App\Services\Traits\ModelCRUDs\Buyer\BuyerCRUD;
use App\Services\Traits\ModelCRUDs\General\CartCRUD;
use App\Services\Traits\ModelCRUDs\General\ProductLocationAndTrackingCRUD;


trait AdminReferralAbstraction
{   
    //inherits all their methods:
    use AdminCRUD;
    use BuyerCRUD;
    use CartCRUD;
    use ProductLocationAndTrackingCRUD;

    //activate or deactivate referral program
    protected function AdminUpdateReferralDetailsService(Request $request): bool
    {
        $queryKeysValues = [
            'unique_admin_id' => $request?->unique_admin_id
        ];
        $newKeysValues = $request?->except('unique_admin_id');

        $ref_detail_was_updated = $this?->AdminUpdateSpecificService($queryKeysValues, $newKeysValues);
        
        return   $ref_detail_was_updated;
    }


    protected function AdminFetchReferralDetailsService(Request $request)//: array
    {
        $referral_details = [];

        //first read all the admin ref details:
        $queryKeysValues = [
            'unique_admin_id' => $request?->unique_admin_id
        ];
        $adminDetails = $this?->AdminReadSpecificService($queryKeysValues);

        //Now get the count of the buyer links:
        $queryParam = "buyer_referral_link";
        $buyerDetails = $this?->BuyerReadSpecificAllTestNotNullService($queryParam);
        
        $ref_count = $buyerDetails?->count();

        //add all to the data array:
        //$all_buyer_collect =  $this?->BuyerReadAllLazyService();
        $all_bonus_gen_so_far = $buyerDetails?->pluck('buyer_total_referral_bonus'); 

        $sum_bonus_gen_so_far = $all_bonus_gen_so_far?->sum();
        
        $referral_details = [
            'is_ref_active' => $adminDetails['is_referral_prog_activated'],
            'ref_bonus_currency' => $adminDetails['referral_bonus_currency'],
            'ref_bonus' =>  $adminDetails['referral_bonus'],
            'ref_links_total' => $ref_count,
            'bonus_generated_so_far' => $sum_bonus_gen_so_far
        ];
        return  $referral_details;
    }


    protected function AdminDisableReferralProgramService(Request $request): bool
    {
        $queryKeysValues = ['unique_admin_id' => $request?->unique_admin_id];
        $newKeysValues = [
            'is_referral_prog_activated' => false,
            'referral_bonus' => null,
            'referral_bonus_currency' => null
        ];

        $ref_program_was_disabled = $this?->AdminUpdateSpecificService($queryKeysValues, $newKeysValues);
        
        return $ref_program_was_disabled;
    }

    protected function  AdminFetchGeneralStatisticsService(Request $request): array
    {
        //first name and last name
        $queryKeysValues = [
            'unique_buyer_id' => $request?->unique_buyer_id
        ];

        $queryKeysValues = [
            'payment_status' => 'pending'
        ];
        //all pending carts of this user:
        $all_pending_carts = $this?->CartReadAllLazySpecificService($queryKeysValues)?->count();

        $queryKeysValues = [
            'payment_status' => 'cleared'
        ];

        $cartModel = $this?->CartReadAllLazySpecificService($queryKeysValues);
        //all cleared carts of this user:
        $all_cleared_carts = $cartModel?->count();

        //total transactions so far:
        $total_transaction = $cartModel?->pluck('purchase_price')?->sum();

        //sales volume:
        $sales_volume_average = ( ($total_transaction/$all_cleared_carts) / $total_transaction ) * 100;

        $all_cleared_cart_ids = $cartModel?->pluck('unique_cart_id');

        
        $all_tracked_goods_count = $this?->ProductLocationAndTrackingReadAllLazyService()?->count();
        
        return [
            'all_pending_carts' => $all_pending_carts,
            'all_cleared_carts' => $all_cleared_carts,
            'total_transaction' =>  $total_transaction,
            'all_tracked_goods' => $all_tracked_goods_count,
            'sales_volume_average' => $sales_volume_average
        ];
    }
}

?>