<?php 

namespace App\Services\Traits\ModelAbstractions\Buyer;;

use App\Services\Traits\ModelCRUDs\Buyer\BuyerCRUD;
use App\Services\Traits\ModelCRUDs\Admin\AdminCRUD;

use Illuminate\Http\Request;

trait BuyerReferralAbstraction
{   
    //inherits all their methods:
    use BuyerCRUD;
    use AdminCRUD;
    
    protected function BuyerGenReferralLinkService(Request $request): string | bool
    {
        $referral_link = null;

        //first check if admin has activated the referral program:
        $admin_details = $this->AdminReadAllService()->first();
        if(!$admin_details )
        {
            return false;
        }

        $is_referral_activated = $admin_details->is_referral_prog_activated;

        if(!$is_referral_activated)
        {
            return false;
        }

        $buyer_id = $request->unique_buyer_id;

        //check the database if ref link is present:
        $queryKeysValues = ['unique_buyer_id' => $buyer_id];
        $db_referral_link = $this?->BuyerReadSpecificService($queryKeysValues)?->buyer_referral_link;
        if( $db_referral_link)
        {
            $referral_link =  $db_referral_link;
        }
        else
        {
            //returns https://wicartit.com.com:
            $current_domain = $request->getSchemeAndHttpHost();

            //if it is activated, continue:
            $sub_referral_url = "Backend/public/api/v1/buyer/referral/{$buyer_id}";

            $referral_link = $current_domain . $sub_referral_url;
            //e.g https://wicartit.com/public/api/v1/buyers/{buyer_id}

            //$queryKeysValues = ['unique_buyer_id' => $buyer_id];

            $newKeysValues = ['buyer_referral_link' => $referral_link];

            //save in the referral table: 
            $referral_link_was_saved =  $this->BuyerUpdateSpecificService($queryKeysValues, $newKeysValues);
            if(!$referral_link_was_saved)
            {
                throw new \Exception("Error in saving new referral link! Please try generating another one again");
            }
        }

        return $referral_link;
    }
    

    protected function BuyerGetReferralBonusService(Request $request): string | bool
    {
        //first check if admin has activated the referral program:
        $admin_details = $this->AdminReadAllService()->first();
        if(!$admin_details)
        {
            return false;
        }

        $is_referral_activated = $admin_details->is_referral_prog_activated;

        if(!$is_referral_activated)
        {
            return false;
        }

        //then check the admin referral program bonus currency:
        $bonus_currency = $admin_details->referral_bonus_currency;

        //now back to the buyer, query for their total accumulated bonus:
        $queryKeysValues = ['unique_buyer_id' => $request->unique_buyer_id];
        $referral_bonus = $this?->BuyerReadSpecificService($queryKeysValues)?->buyer_total_referral_bonus;

        //return array:
        $ref_bonus_details = [
            'ref_bonus_currency' => $bonus_currency,
            'ref_bonus_amount' => $referral_bonus
        ];

        return $ref_bonus_details;
    }


    protected function BuyerFollowReferralLinkService(string $unique_buyer_id)//: string
    {
        //first check if admin has activated the referral program:
        $admin_details = $this->AdminReadAllService()->first();
        if(!$admin_details )
        {
            return false;
        }

        $is_referral_activated = $admin_details->is_referral_prog_activated;

        if(!$is_referral_activated)
        {
            return false;
        }

        //get the admin bonus per click:
        $bonus_per_click = $admin_details->referral_bonus;

        //check the buyer table and add bonus acordingly:
        $queryKeysValues = ['unique_buyer_id' => $unique_buyer_id];
        $db_referral_bonus = $this?->BuyerReadSpecificService($queryKeysValues)?->buyer_total_referral_bonus;

        //cast values:
        //add the two values together and update:
        (float)$db_referral_bonus += (float)$bonus_per_click;

        //update this new value in database:
        $newKeysValues = [
            'buyer_total_referral_bonus' => $db_referral_bonus
        ];
        $new_referral_was_updated = $this->BuyerUpdateSpecificService($queryKeysValues, $newKeysValues);

        return $new_referral_was_updated;
    }


}