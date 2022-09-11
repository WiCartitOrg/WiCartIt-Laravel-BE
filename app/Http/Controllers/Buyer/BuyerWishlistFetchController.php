<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

use App\Validators\Buyer\BuyerWishlistFetchRequestRules;

use App\Services\Interfaces\Buyer\BuyerWishlistFetchInterface;
use App\Services\Traits\ModelAbstractions\Buyer\BuyerWishlistFetchAbstraction;

final class BuyerWishlistFetchController extends Controller implements BuyerWishlistFetchInterface
{
   use BuyerWishlistFetchAbstraction;
   use BuyerWishlistFetchRequestRules;

   public function __construct()
   {
      //$this->createBuyerDefault();
   }

   public function FetchAllWishlistsIdsOnly(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this->fetchAllWishlistIdsOnlyRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Invalid Input Provided!");
         }
         
         $detailsFound = $this->BuyerFetchAllWishlistsIdsOnlyService($request);
         if( empty($detailsFound) )
         {
            throw new \Exception("Wishlists Ids not found! Ensure that all Wishlists are not empty!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'FetchSuccess!',
            'wishlist_details' => $detailsFound
         ];

      }
      catch(\Exception $ex)
      {

         $status = [
            'code' => 0,
            'serverStatus' => 'FetchError!',
            'short_description' => $ex->getMessage()
         ];
         return response()->json($status, 400);
      }
      /*finally
      {*/
         return response()->json($status, 200);
      //}
   }


   public function FetchEachWishlistDetailByID(Request $request)
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this->fetchEachWishlistDetailByIDRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Invalid Input Provided!");
         }
         
         $detailsFound = $this->BuyerFetchEachWishlistDetailByIDService($request);
         if( !$detailsFound )
         {
            throw new \Exception("Buyer Wishlist Detail not found!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'FetchSuccess!',
            'wishlist_detail' => $detailsFound
         ];

      }
      catch(\Exception $ex)
      {

         $status = [
            'code' => 0,
            'serverStatus' => 'FetchError!',
            'short_description' => $ex->getMessage()
         ];
         return response()->json($status, 400);
      }
      /*finally
      {*/
         return response()->json($status, 200);
      //}
   }

   
   public function FetchAllWishlistsDetails(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this->fetchAllWishlistsDetailsRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Invalid Input Provided!");
         }
         
         $detailsFound = $this->BuyerFetchAllWishlistsDetailsService($request);
         if( empty($detailsFound) )
         {
            throw new \Exception("Wishlists not found! Ensure that all Wishlists are not empty!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'FetchSuccess!',
            'wishlist_detail' => $detailsFound
         ];

      }
      catch(\Exception $ex)
      {

         $status = [
            'code' => 0,
            'serverStatus' => 'FetchError!',
            'short_description' => $ex->getMessage()
         ];
         return response()->json($status, 400);
      }
      /*finally
      {*/
         return response()->json($status, 200);
      //}
   }


   public function FetchAllWishlistProductsIDsOnly(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this->fetchAllWishlistProductsIDsOnlyRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Access Error, Not a logged in user!");
         }
         
         //this should return in chunks or paginate:
         $detailsFound = $this->BuyerFetchAllWishlistProductsIDsOnlyService($request);
         if( empty($detailsFound) )
         {
            throw new \Exception("Wishlist Product IDs not found!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'FetchSuccess!',
            'allWishlistIDs' => $detailsFound
         ];
      }
      catch(\Exception $ex)
      {
         $status = [
            'code' => 0,
            'serverStatus' => 'FetchError!',
            'short_description' => $ex->getMessage()
         ];
         return response()->json($status, 400);
      }
      /*finally
      {*/
         return response()->json($status, 200);
      //}
   }

}
