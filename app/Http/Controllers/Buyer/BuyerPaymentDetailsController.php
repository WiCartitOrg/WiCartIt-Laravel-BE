<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

use App\Services\Interfaces\Buyer\BuyerPaymentDetailsInterface;

use App\Services\Traits\ModelAbstractions\Buyer\BuyerPaymentDetailsAbstraction;
use App\Validators\Buyer\BuyerPaymentDetailsRequestRules;


final class BuyerPaymentDetailsController extends Controller implements BuyerPaymentDetailsInterface
{
   
   use BuyerPaymentDetailsAbstraction;
   use BuyerPaymentDetailsRequestRules;
    
   public function __construct()
   {
      //$this?->createBuyerDefault();
   }


   public function UploadCardDetails(Request $request): JsonResponse
   {
      $status = array();
      
      try
      {
         //get rules from validator class:
         $reqRules = $this?->uploadCardDetailsRules();

         //validate here:
         $validator = Validator::make($request?->all(), $reqRules);

         if($validator?->fails())
         {
            throw new \Exception("Invalid Inputs Provided!");
         }
         
         //this should return in chunks or paginate:
         $detailsSaved = $this?->BuyerUploadCardDetailsService($request);
         if(!$detailsSaved)
         {
            throw new \Exception("Card Details not uploaded successfully!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'UploadSuccess!',
         ];

      }
      catch(\Exception $ex)
      {
         $status = [
            'code' => 0,
            'serverStatus' => 'UploadError!',
            'short_description' => $ex?->getMessage()
         ];
         return response()?->json($status, 400);
      }
      /*finally
      {*/
         return response()?->json($status, 200);
      //}
   }


   public function FetchEachCardDetails(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this?->fetchEachCardDetailsRules();

         //validate here:
         $validator = Validator::make($request?->all(), $reqRules);

         if($validator?->fails())
         {
            throw new \Exception("Access Error, Not logged in yet!");
         }
         
         //this should return in chunks or paginate:
         $detailsFound = $this?->BuyerFetchEachCardDetailsService($request);
         if( empty($detailsFound) )
         {
            throw new \Exception("No Card Details found!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'FetchSuccess!',
            'buyers' => $detailsFound
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

    
   //tested with get but not with put...
   public function UploadAccountDetails(Request $request): JsonResponse
   {
      $status = array();
      try
      {
         //get rules from validator class:
         $reqRules = $this?->uploadAccountDetailsRules();

         //validate here:
         $validator = Validator::make($request?->all(), $reqRules);

         if($validator?->fails())
         {
            throw new \Exception("Invalid Input provided!");
         }

         //create without mass assignment:
         $details_has_saved = $this?->BuyerUploadAccountDetailsService($request);
         if(!$details_has_saved/*false*/)
         {
            throw new \Exception("Account Details not saved!");
         }

         $status = [
            'code' => 1,    
            'serverStatus' => 'DetailsSaved!',
         ];
      }
      catch(\Exception $ex)
      {
         $status = [
            'code' => 0,
            'serverStatus' => 'DetailsNotSaved!',
            'short_description' => $ex?->getMessage(),
         ];
         return response()?->json($status, 400);
      }
      /*finally
       {*/
           return response()?->json($status, 200);
       /*}*/
   }


   public function FetchEachAccountDetails(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this?->fetchEachAccountDetailsRules();

         //validate here:
         $validator = Validator::make($request?->all(), $reqRules);

         if($validator?->fails())
         {
            throw new \Exception("Access Error, not a logged-in user!");
         }

         $basic_account_details_fetched = $this?->BuyerFetchEachAccountDetailsService($request);
            
         if(empty($basic_account_details_fetched))
         {
            throw new \Exception("Details Empty, please update to get values");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'FetchSuccess!',
            'accountDetails' => $basic_account_details_fetched
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

}
