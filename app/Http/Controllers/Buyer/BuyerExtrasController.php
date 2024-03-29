<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

use App\Validators\Buyer\BuyerExtrasRequestRules;

use App\Services\Interfaces\Buyer\BuyerExtrasInterface;
use App\Services\Traits\ModelAbstractions\Buyer\BuyerExtrasAbstraction;
use App\Services\Traits\ModelAbstractions\Buyer\BuyerCommentRateAbstraction;

use App\Services\Traits\Utilities\PaginateCustomCollection;

final class BuyerExtrasController extends Controller implements BuyerExtrasInterface
{
   use BuyerExtrasAbstraction;
   use BuyerCommentRateAbstraction;
   use BuyerExtrasRequestRules;
   use PaginateCustomCollection;
   

   public function __construct()
   {
        //$this?->createAdminDefault();
   }

   //Track cleared cart product content(s)
   public function TrackProductsLocation(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this?->trackProductsLocationRules();

         //validate here:
         $validator = Validator::make($request?->all(), $reqRules);

         if($validator?->fails())
         {
            throw new \Exception("Access Error! Not Logged In Yet!");
         }

         $locationDetails = $this?->BuyerTrackProductsLocationService($request);
         if( empty($locationDetails) ) 
         {
            throw new \Exception("Product Tracking Details Not Found!");
         }
         if(!$locationDetails/*false*/)
         {
            throw new \Exception("Only cleared products can be tracked!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'ProductLocationTrackingSuccess!',
            'trackDetails' => $locationDetails,
         ];

      }
      catch(\Exception $ex)
      {
         $status = [
            'code' => 0,
            'serverStatus' => 'ProductLocationTrackingFailure!',
            'short_description' => $ex?->getMessage(),
         ];
         return response()?->json($status, 400);
      }
      /*finally
      {*/
         return response()?->json($status, 200);
      //}

   }


   public function ConfirmProductsDelivery(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this?->confirmProductsDeliveryRules();

         //validate here:
         $validator = Validator::make($request?->all(), $reqRules);

         if($validator?->fails())
         {
            throw new \Exception("Access Error! Not Logged In Yet!");
         }
         
         $delivery_confirmed = $this?->BuyerConfirmDeliveryService($request);
         if(!$delivery_confirmed)
         {
            throw new \Exception("Delivery Not Confirmed Yet!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'DeliveryConfirmed!',
         ];

      }
      catch(\Exception $ex)
      {
         $status = [
            'code' => 0,
            'serverStatus' => 'DeliveryNotConfirmed!',
            'short_description' => $ex?->getMessage()
         ];
         return response()?->json($status, 400);
      }
      /*finally
      {*/
         return response()?->json($status, 200);
      //}

   }


   public function CommentRateService(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this?->commentRateServiceRules();

         //validate here:
         $validator = Validator::make($request?->all(), $reqRules);

         if($validator?->fails())
         {
            throw new \Exception("Invalid Input Provided!");
         }

         $commentRateSaved = $this?->BuyerCommentRateService($request);

         if(!$commentRateSaved)
         {
            throw new \Exception("Wasn't able to save comments/ratings successfully!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'CreationSuccess!'
         ];

      }
      catch(\Exception $ex)
      {
         $status = [
            'code' => 0,
            'serverStatus' => 'CreationFailure!',
            'short_description' => $ex?->getMessage()
         ];
         return response()?->json($status, 400);
      }
      /*finally
      {*/
         return response()?->json($status, 200);
      //}

   }


   public function ViewOtherCommentsRates(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this?->viewOtherCommentsRatesRules();

         //validate here:
         $validator = Validator::make($request?->all(), $reqRules);

         if($validator?->fails())
         {
            throw new \Exception("Access Error, Not logged in yet!");
         }

         //this should return in chunks or paginate:
         $detailsFound = $this?->BuyerViewOtherBuyersCommentRateService($request);
         if(!$detailsFound)
         {
            throw new \Exception("Buyers Comments and Ratings not found!");
         }
         
         //Arrange pagination through new collection:
         $paginateCommentsRates = $this->Paginate(collect($detailsFound), 10);

         $status = [
            'code' => 1,
            'serverStatus' => 'Comment/Ratings Found!',
            'comment_rates' => $paginateCommentsRates->toJson(),
            'links' => $paginateCommentsRates->links(),
         ];
      }
      catch(\Exception $ex)
      {
         $status = [
            'code' => 0,
            'serverStatus' => 'Retrieval Error!',
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

         if($validator?->fails())
         {
            throw new \Exception("Access Error, Not Logged In Yet!");
         }

         //this should return in chunks or paginate:
         $generalStatisticsDetails = $this?->BuyerFetchGeneralStatisticsService($request);
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
