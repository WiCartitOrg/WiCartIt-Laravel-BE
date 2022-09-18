<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

use App\Validators\Buyer\BuyerReferralRequestRules;

use App\Services\Interfaces\Buyer\BuyerReferralInterface;
use App\Services\Traits\ModelAbstractions\Buyer\BuyerReferralAbstraction;

final class BuyerReferralController extends Controller implements BuyerReferralInterface
{
   use BuyerReferralRequestRules;
   use BuyerReferralAbstraction;

   public function __construct()
   {
      //$this->createAdminDefault();
   }

   public function GenUniqueReferralLink(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this->genUniqueReferralLinkRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Access Error, Not logged in yet!");
         }

         $unique_referral_link = $this->BuyerGenReferralLinkService($request);
         if(!$unique_referral_link)
         {
            throw new \Exception("Referral Program Not Activated Yet!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'ReferralLinkFormed!',
            'referral_link' =>  $unique_referral_link
         ];

      }
      catch(\Exception $ex)
      {
         $status = [
            'code' => 0,
            'serverStatus' => 'ReferralLinkNotFormed!',
            'short_description' => $ex->getMessage(),
         ];
         return response()->json($status, 400);
      }/*finally
      {*/
         return response()->json($status, 200);
      //}   
   }


   public function GetReferralBonus(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this->getReferralBonusRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Access Error, Not logged in yet!");
         }

         $referral_bonus_details = $this->BuyerGetReferralBonusService($request);

         if(!$referral_bonus_details)
         {
            throw new \Exception("Referral Program Not Activated Yet!");
         }

         if( empty($ref_bonus_details) ) 
         {
            throw new \Exception("Buyer Referral Bonus not found!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'FetchSuccess!',
            'referral_bonus_details' => $referral_bonus_details
         ];

      }
      catch(\Exception $ex)
      {
         $status = [
            'code' => 0,
            'serverStatus' => 'FetchError!',
            'short_description' => $ex->getMessage(),
         ];
         return response()->json($status, 400);
      }/*finally
      {*/
         return response()->json($status, 200);
      //}
      
   }


   public function FollowReferralLink(Request $request, $unique_buyer_id): JsonResponse
   {
      $status = array();

      //redirect to our homepage:
      //$redirect_link = redirect()->to('https://wicartit.com');
      try
      {
         //get rules from validator class:
         $reqRules = $this->followReferralLinkRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Access Error, Not logged in yet!");
         }

         $bonus_was_recorded = $this->BuyerFollowReferralLinkService($unique_buyer_id);
         if(!$bonus_was_recorded)
         {
           //though not expecting error here: 
           throw new \Exception("Referral Program Not Activated Yet!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'ReferralBonusUpdateSuccess!',
            'redirect_to_home_page' => true,
         ];
      }
      catch(\Exception $ex)
      {
         $status = [
            'code' => 0,
            'serverStatus' => 'ReferralBonusUpdateFailure!',
            'short_description' => $ex->getMessage(),
         ];
         return response()->json($status, 400);
      }
      /*finally
      {*/
         //redirect to our homepage:
         return response()->json($status, 200);
      //}
   }

}