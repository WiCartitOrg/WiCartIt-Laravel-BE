<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

use App\Validators\Buyer\BuyerCartEditRequestRules;

use App\Services\Interfaces\Buyer\BuyerCartEditInterface;
use App\Services\Traits\ModelAbstractions\Buyer\BuyerCartEditAbstraction;
use App\Services\Traits\ModelAbstraction\PaymentAbstraction;

final class BuyerCartEditController extends Controller implements BuyerCartEditInterface
{
   use BuyerCartEditAbstraction, BuyerCartEditRequestRules;

   public function __construct()
   {
      //$this->createAdminDefault();
   }

   public function AddProductsToPendingCart(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this->addProductsToPendingCartRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Invalid Input provided!");
         }

         //this should return in chunks or paginate:
         $pending_cart_was_created = $this->BuyerAddProductToPendingCartService($request);
         if(!$pending_cart_was_created) 
         {
            throw new \Exception("Cart Not Created successfully!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'CartCreateSuccess!',
         ];

      }
      catch(\Exception $ex)
      {
         $status = [
            'code' => 0,
            'serverStatus' => 'CartCreateFailure!',
            'short_description' => $ex->getMessage()
         ];

         return response()->json($status, 400);
      }
      /*finally
      {*/
         return response()->json($status, 200);
      //}

   }


   public function EditProductsOnPendingCart(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this->editProductsOnPendingCartRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Invalid Input Provided!");
         }

         $cart_was_edited = $this->BuyerEditProductsOnPendingCartService($request);

         if(!$cart_was_edited)
         {
            throw new \Exception("Cart contents update failure!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'Cart Contents Changed Successfully!'
         ];

      }
      catch(\Exception $ex)
      {
         $status = [
            'code' => 0,
            'serverStatus' => 'CartUpdateFailure!',
            'short_description' => $ex->getMessage()
         ];

         return response()->json($status, 400);
      }
      /*finally
      {*/
         return response()->json($status, 200);
      //}

   }


   public function DeletePendingCart(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this->deletePendingCartRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Access Error, Not logged in yet!");
         }

         //this should return in chunks or paginate:
         $is_cart_deleted = $this->BuyerDeletePendingCartService($request);
         if(!$is_cart_deleted)
         {
            throw new \Exception("Cart not deleted successfully!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'CartDeletionSuccess!',
         ];
      }
      catch(\Exception $ex)
      {
         $status = [
            'code' => 0,
            'serverStatus' => 'CartDeletionFailure!',
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
