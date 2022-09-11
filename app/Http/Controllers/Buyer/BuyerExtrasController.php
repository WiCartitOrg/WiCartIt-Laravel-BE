<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Validators\BuyerExtrasRequestRules;

use App\Services\Interfaces\BuyerExtrasInterface;
use App\Services\Traits\ModelAbstraction\BuyerExtrasAbstraction;
use App\Services\Traits\ModelAbstraction\CommentRateAbstraction;

final class BuyerExtrasController extends Controller //implements BuyerExtrasInterface
{
   use BuyerExtrasRequestRules;
   use BuyerExtrasAbstraction;
   use CommentRateAbstraction;
   

   public function __construct()
   {
        //$this->createAdminDefault();
   }

   public function TrackGoods(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this->trackGoodsRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails()){
            throw new \Exception("Invalid Input provided!");
         }

         //this should return in chunks or paginate:
         $locationDetails = $this->BuyerTrackGoodsService($request);
         if( empty($locationDetails) ) 
         {
            throw new \Exception("Tracking Details Not Found!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'Tracking Details Found!',
            'trackDetails' => $locationDetails,

         ];

      }
      catch(\Exception $ex)
      {
         $status = [
            'code' => 0,
            'serverStatus' => 'Track Error!',
            'short_description' => $ex->getMessage(),
         ];

      }
      finally
      {
         return response()->json($status, 200);
      }

   }

   public function FetchGeneralStatistics(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this->fetchGeneralStatisticsRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails()){
            throw new \Exception("Access Error, Not Logged In Yet!");
         }

         //this should return in chunks or paginate:
         $generalStatisticsDetails = $this->BuyerFetchGeneralStatisticsService($request);
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
            'short_description' => $ex->getMessage(),
         ];

      }
      /*finally
      {*/
         return response()->json($status, 200);
      //}

   }

   //first display the summary of all pending(not paid yet) or cleared cart(paid)
   public function ConfirmDelivery(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this->confirmDeliveryRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Access Error, Not logged in yet!");
         }
         
         //this should return in chunks or paginate:
         $is_delivery_confirmed = $this->BuyerConfirmDeliveryService($request);
         if(!$is_delivery_confirmed)
         {
            throw new \Exception("Delivery Not Confirmed Yet!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'Delivery Confirmed!',
         ];

      }
      catch(\Exception $ex)
      {

         $status = [
            'code' => 0,
            'serverStatus' => 'Search Error!',
            'short_description' => $ex->getMessage()
         ];

      }
      finally
      {
         return response()->json($status, 200);
      }

   }


   public function CommentRate(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this->commentRateRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Invalid Input Provided!");
         }

         $comment_rate_has_saved = $this->BuyerCommentRateService($request);

         if(!$comment_rate_has_saved)
         {
            throw new \Exception("Wasn't able to save comments/ratings successfully!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'Comments/Ratings saved successfully!'
         ];

      }
      catch(\Exception $ex)
      {
         $status = [
            'code' => 0,
            'serverStatus' => 'Creation Error!',
            'short_description' => $ex->getMessage()
         ];

      }
      finally
      {
         return response()->json($status, 200);
      }

   }


   public function ViewOtherCommentsRates(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this->viewOtherCommentsRates();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Access Error, Not logged in yet!");
         }

         //this should return in chunks or paginate:
         $detailsFound = $this->BuyerViewOtherBuyersCommentRateService($request);
         if(!$detailsFound)
         {
            throw new \Exception("Buyers Comments and Ratings not found!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'Comment/Ratings Found!',
            'comment_rates' => $detailsFound
         ];

      }catch(\Exception $ex)
      {

         $status = [
            'code' => 0,
            'serverStatus' => 'Retrieval Error!',
            'short_description' => $ex->getMessage()
         ];

      }finally
      {
         return response()->json($status, 200);
      }

   }


}
