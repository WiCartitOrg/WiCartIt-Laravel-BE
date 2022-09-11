<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

use App\Validators\Vendor\VendorCartFetchRequestRules;

use App\Services\Interfaces\Vendor\VendorCartFetchInterface;
use App\Services\Traits\ModelAbstractions\Vendor\VendorCartFetchAbstraction;

final class VendorCartFetchController extends Controller implements VendorCartFetchInterface
{
   use VendorCartFetchAbstraction;
   use VendorCartFetchRequestRules;

   public function __construct()
   {
      //$this->createVendorDefault();
   }


   //first display the summary of all pending(not paid yet) or cleared cart(paid)
   public function FetchRelatedCartProductsDetails(Request $request): JsonResponse
   {
      $status = array();
 
      try
      {
         //get rules from validator class:
         $reqRules = $this->fetchRelatedCartProductsDetailsRules();
 
         //validate here:
         $validator = Validator::make($request->all(), $reqRules);
 
         if($validator->fails())
         {
            throw new \Exception("Invalid Input Provided!");
         }
          
         //this should return in chunks or paginate:
         $detailsFound = $this->VendorFetchRelatedCartProductsDetailsService($request);
         if( empty($detailsFound) )
         {
            throw new \Exception("Cart details not found!");
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
   public function FetchBuyersCustomerDetails(Request $request)
   {
      $status = array();
 
      try
      {
         //get rules from validator class:
         $reqRules = $this->fetchBuyersCustomerDetailsRules();
 
         //validate here:
         $validator = Validator::make($request->all(), $reqRules);
 
         if($validator->fails())
         {
            throw new \Exception("Invalid Input Provided!");
         }
          
         //this should return in chunks or paginate:
         $detailsFound = $this->VendorFetchBuyersCustomerDetailsService($request);
         if( empty($detailsFound) )
         {
            throw new \Exception("Buyer Customer Details not found!");
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
