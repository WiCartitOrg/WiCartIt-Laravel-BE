<?php 

namespace App\Services\Traits\ModelAbstractions\Buyer;;

use App\Services\Traits\ModelCRUD\BuyerCRUD;
use App\Services\Traits\ModelCRUD\AdminCRUD;

use Illuminate\Http\Request;

trait BuyerReferralAbstraction
{   
    //inherits all their methods:
    use BuyerCRUD;
    use AdminCRUD;
    
    protected function BuyerGenReferralLinkService(Request $request)//: string
    {
        $ref_link = null;

        //first check if admin has activated the referral program:
        $admin_details = $this->AdminReadAllService()->first();
        if(!$admin_details )
        {
            throw new \Exception("Referral Program Not Activated Yet!");
        }

        $is_referral_activated = $admin_details->is_referral_prog_activated;

        if(!$is_referral_activated)
        {
            throw new \Exception("Referral Program Not Activated Yet!");
        }

        $buyer_id = $request->unique_buyer_id;

        //check the database if ref link is present:
        $queryKeysValues = ['unique_buyer_id' => $buyer_id];
        $db_ref_link = $this?->BuyerReadSpecificService($queryKeysValues)?->buyer_referral_link;
        if($db_ref_link)
        {
            $ref_link = $db_ref_link;
        }
        else
        {
            //if it is activated, continue:
            $sub_ref_url = "/Hodaviah/Backend/public/api/v1/buyer/referral/{$buyer_id}";

            $current_domain = $request->getSchemeAndHttpHost();
            //returns https://hodaviah.com

            $ref_link = $current_domain . $sub_ref_url;
            //e.g https://internshire.com/public/api/v1/buyers/{buyer_id}

            //$queryKeysValues = ['unique_buyer_id' => $buyer_id];

            $newKeysValues = ['buyer_referral_link' => $ref_link];

            //save in the referral table: 
            $is_ref_link_saved =  $this->BuyerUpdateSpecificService($queryKeysValues, $newKeysValues);
            if(!$is_ref_link_saved)
            {
                throw new \Exception("Error in Saving referral link! Please try generating another one again");
            }
        }

        return $ref_link;
        //return $db_ref_link;
    }
    

    protected function BuyerGetReferralBonusService(Request $request)//: string
    {
        //first check if admin has activated the referral program:
        $admin_details = $this->AdminReadAllService()->first();
        if(!$admin_details )
        {
            throw new \Exception("Referral Program Not Activated Yet!");
        }

        $is_referral_activated = $admin_details->is_referral_prog_activated;

        if(!$is_referral_activated)
        {
            throw new \Exception("Referral Program Not Activated Yet!");
        }

        //then check the admin referral program bonus currency:
        $bonus_currency = $admin_details->referral_bonus_currency;

        //now back to the buyer, query for their total accumulated bonus:
        $queryKeysValues = ['unique_buyer_id' => $request->unique_buyer_id];
        $ref_bonus = $this?->BuyerReadSpecificService($queryKeysValues)?->buyer_total_referral_bonus;

        //return array:
        $ref_bonus_details = [
            'ref_bonus_currency' => $bonus_currency,
            'ref_bonus_amount' => $ref_bonus
        ];

        return $ref_bonus_details;
    }


    protected function BuyerReferralLinkUseService(string $unique_buyer_id)//: string
    {
        //first check if admin has activated the referral program:
        $admin_details = $this->AdminReadAllService()->first();
        if(!$admin_details )
        {
            throw new \Exception("Referral Program Not Activated Yet!");
        }

        $is_referral_activated = $admin_details->is_referral_prog_activated;

        if(!$is_referral_activated)
        {
            throw new \Exception("Referral Program Not Activated Yet!");
        }

        //get the admin bonus per click:
        $bonus_per_click = $admin_details->referral_bonus;

        //check the buyer table and add bonus acordingly:
        $queryKeysValues = ['unique_buyer_id' => $unique_buyer_id];
        $db_ref_bonus = $this?->BuyerReadSpecificService($queryKeysValues)?->buyer_total_referral_bonus;

        //cast values:
        //add the two values together and update:
        (float)$db_ref_bonus += (float)$bonus_per_click;

        //update this new value in database:
        $newKeysValues = ['buyer_total_referral_bonus' => $db_ref_bonus];
        $is_new_ref_updated = $this->BuyerUpdateSpecificService($queryKeysValues, $newKeysValues);

        return $is_new_ref_updated;
    }


}