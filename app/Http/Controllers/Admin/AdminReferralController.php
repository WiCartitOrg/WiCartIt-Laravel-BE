<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

use App\Services\Interfaces\Admin\AdminReferralInterface;

use App\Services\Traits\ModelAbstractions\Admin\AdminReferralAbstraction;
use App\Validators\Admin\AdminReferralRequestRules;

final class AdminReferralController extends Controller implements AdminReferralInterface
{
   use AdminReferralAbstraction;
   use AdminReferralRequestRules;

   public function __construct()
   {
        //$this?->createAdminDefault();
   }


   public function UpdateReferralDetails(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this?->updateReferralDetailsRules();

         //validate here:
         $validator = Validator::make($request?->all(), $reqRules);

         if($validator?->fails())
         {
            throw new \Exception("Invalid Input Provided!");
         }
         
         $ref_state_has_changed = $this?->AdminUpdateReferralDetailsService($request);
         if( !$ref_state_has_changed )
         {
            throw new \Exception("Couldn't change referral program status!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'ReferralDetailsSaved!',
         ];

      }
      catch(\Exception $ex)
      {
         $status = [
            'code' => 0,
            'serverStatus' => 'ReferralDetailsNotSaved!',
            'short_description' => $ex?->getMessage()
         ];
         return response()?->json($status, 400);
      }
      /*finally
      {*/
         return response()?->json($status, 200);
      //}
   }

   
   public function FetchReferralDetails(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this?->fetchReferralDetailsRules();

         //validate here:
         $validator = Validator::make($request?->all(), $reqRules);

         if($validator?->fails())
         {
            throw new \Exception("Access Error, Not logged in yet!");
         }
         
         $referralDetailsFound = $this?->AdminFetchReferralDetailsService($request);
         if(empty($referralDetailsFound))
         {
            throw new \Exception("Couldn't find any referral details!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'FetchSuccess!',
            'referral_details' => $referralDetailsFound
         ];
      }
      catch(\Exception $ex)
      {
         $status = [
            'code' => 0,
            'serverStatus' => 'FetchError!',
            'short_description' => $ex?->getMessage()
         ];
         return response()?->json($status, 400);
      }
      /*finally
      {*/
         return response()?->json($status, 200);
      //}
   }


   public function DisableReferral(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this?->disableReferralRules();

         //validate here:
         $validator = Validator::make($request?->all(), $reqRules);

         if($validator?->fails())
         {
            throw new \Exception("Access Error, Not logged in yet!");
         }
         
         $referralDisabled = $this?->AdminDisableReferralProgramService($request);

         if(!$referralDisabled)
         {
            throw new \Exception("Couldn't disable the referral program");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'referralDisabled!',
         ];
      }
      catch(\Exception $ex)
      {
         $status = [
            'code' => 0,
            'serverStatus' => 'referralNotDisabled!',
            'short_description' => $ex?->getMessage()
         ];
         return response()?->json($status, 400);
      }
      /*finally
      {*/
         return response()?->json($status, 200);
      //}
   }


   public function FetchGeneralStatistics(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this?->fetchGeneralStatisticsRules();

         //validate here:
         $validator = Validator::make($request?->all(), $reqRules);

         if($validator?->fails()){
            throw new \Exception("Access Error, Not Logged In Yet!");
         }

         //this should return in chunks or paginate:
         $generalStatisticsDetails = $this?->AdminFetchGeneralStatisticsService($request);
         if( empty($generalStatisticsDetails) ) 
         {
            throw new \Exception("Dashboard statistics details Not Found!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'DetailsFound!',
            'statDetails' =>  $generalStatisticsDetails,

         ];

      }
      catch(\Exception $ex)
      {
         $status = [
            'code' => 0,
            'serverStatus' => 'DetailsNotFound!',
            'short_description' => $ex?->getMessage(),
         ];

      }
      /*finally
      {*/
         return response()?->json($status, 200);
      //}

   }


}