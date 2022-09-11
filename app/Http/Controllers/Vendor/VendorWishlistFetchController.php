<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

use App\Validators\Vendor\VendorWishlistFetchRequestRules;

use App\Services\Interfaces\Vendor\VendorWishlistFetchInterface;
use App\Services\Traits\ModelAbstractions\Vendor\VendorWishlistFetchAbstraction;

final class VendorWishlistFetchController extends Controller implements VendorWishlistFetchInterface
{
   use VendorWishlistFetchAbstraction;
   use VendorWishlistFetchRequestRules;

   public function __construct()
   {
      //$this->createVendorDefault();
   }


   //first display the summary of all pending(not paid yet) or cleared wishlist(paid)
   public function FetchRelatedWishlistProductsDetails(Request $request): JsonResponse
   {
      $status = array();
 
      try
      {
         //get rules from validator class:
         $reqRules = $this->fetchRelatedWishlistProductsDetailsRules();
 
         //validate here:
         $validator = Validator::make($request->all(), $reqRules);
 
         if($validator->fails())
         {
            throw new \Exception("Access Error, Not  a valid user!");
         }
          
         //this should return in chunks or paginate:
         $detailsFound = $this->VendorFetchRelatedWishlistProductsDetailsService($request);
         if( empty($detailsFound) )
         {
            throw new \Exception("Related Wishlist Products details not found!");
         }
 
         $status = [
            'code' => 1,
            'serverStatus' => 'FetchSuccess!',
            'employers' => $detailsFound
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
      }
      /*finally
      {*/
         return response()->json($status, 200);
      //}
    } 
   

   //Buyers that have bought products from this particular vendor and how much they have bought:
   public function FetchWishingBuyersDetails(Request $request): JsonResponse
   {
      $status = array();
 
      try
      {
         //get rules from validator class:
         $reqRules = $this->fetchWishingBuyersDetailsRules();
 
         //validate here:
         $validator = Validator::make($request->all(), $reqRules);
 
         if($validator->fails())
         {
            throw new \Exception("Access Error, Not  a valid user!");
         }
          
         //this should return in chunks or paginate:
         $detailsFound = $this->VendorFetchWishingBuyersDetailsService($request);
         if( empty($detailsFound) )
         {
            throw new \Exception("Wishing Buyers Details not found!");
         }
 
         $status = [
            'code' => 1,
            'serverStatus' => 'FetchSuccess!',
            'employers' => $detailsFound
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
      }
      /*finally
      {*/
         return response()->json($status, 200);
      //}
   }

}
