<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

use App\Validators\Buyer\BuyerCartFetchRequestRules;

use App\Services\Interfaces\Buyer\BuyerCartFetchInterface;
use App\Services\Traits\ModelAbstractions\Buyer\BuyerCartFetchAbstraction;

final class BuyerCartFetchController extends Controller implements BuyerCartFetchInterface
{
   use BuyerCartFetchAbstraction;
   use BuyerCartFetchRequestRules;

   public function __construct()
   {
      //$this->createBuyerDefault();
   }


   //first display the summary of all pending(not paid yet) or cleared cart(paid)
   public function FetchCartsByCategory(Request $request): JsonResponse
   {
      $status = array();
 
      try
      {
         //get rules from validator class:
         $reqRules = $this->fetchCartsByCategoryRules();
 
         //validate here:
         $validator = Validator::make($request->all(), $reqRules);
 
         if($validator->fails())
         {
            throw new \Exception("Invalid Input Provided!");
         }
          
         //this should return in chunks or paginate:
         $detailsFound = $this->BuyerFetchCartByCategoryService($request);
         if( empty($detailsFound) )
         {
            throw new \Exception("Cart details not found!");
         }
 
         $status = [
            'code' => 1,
            'serverStatus' => 'Search Success!',
            'employers' => $detailsFound
         ];
      }
      catch(\Exception $ex)
      {
         $status = [
            'code' => 0,
            'serverStatus' => 'Search Error!',
            'short_description' => $ex->getMessage(),
         ];
         return response()->json($status, 400);
      }
      /*finally
      {*/
         return response()->json($status, 200);
      //}
   } 
    


   public function FetchCartsIDsOnly(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this->fetchCartsIDsOnlyRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Invalid Input Provided!");
         }
         
         $detailsFound = $this->BuyerFetchCartsIDsOnlyService($request);
         if( empty($detailsFound) )
         {
            throw new \Exception("Pending Cart Details not found! Ensure that this is not a Cleared Cart ID.");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'FetchSuccess!',
            'cart_details' => $detailsFound
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


   public function FetchAllCartProductsIDsOnly(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this->fetchAllCartProductsIDsOnlyRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Access Error, Not a logged in user!");
         }
         
         //this should return in chunks or paginate:
         $detailsFound = $this->BuyerFetchAllCartProductsIDsOnlyService($request);
         if( empty($detailsFound) )
         {
            throw new \Exception("{ $request->payment_status === 'cleared' ? 'Cleared' : 'Pending'} Carts IDs not found!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'FetchSuccess!',
            'allCartIDs' => $detailsFound
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
