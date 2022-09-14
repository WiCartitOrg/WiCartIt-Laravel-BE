<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

use App\Validators\Buyer\BuyerWishlistEditRequestRules;

use App\Services\Interfaces\Buyer\BuyerWishlistEditInterface;
use App\Services\Traits\ModelAbstractions\Buyer\BuyerWishlistEditAbstraction;
use App\Services\Traits\ModelAbstraction\PaymentAbstraction;

final class BuyerWishlistEditController extends Controller implements BuyerWishlistEditInterface
{
   use BuyerWishlistEditAbstraction;
   use BuyerWishlistEditRequestRules;


   public function __construct()
   {
      //$this->createAdminDefault();
   }


   public function AddProductsToWishlist(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this->addProductsToWishlistRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Invalid Input provided!");
         }

         //this should return in chunks or paginate:
         $wishlist_was_created = $this->BuyerAddProductToWishlistService($request);
         if(!$wishlist_was_created) 
         {
            throw new \Exception("Wishlist Not Created successfully!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'WishlistCreateSuccess!',
         ];

      }
      catch(\Exception $ex)
      {
         $status = [
            'code' => 0,
            'serverStatus' => 'WishlistCreateFailure!',
            'short_description' => $ex->getMessage()
         ];

         return response()->json($status, 400);
      }
      /*finally
      {*/
         return response()->json($status, 200);
      //}

   }


   public function EditProductsOnWishlist(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this->editProductsOnWishlistRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Invalid Input Provided!");
         }

         $wishlist_was_edited = $this->BuyerEditProductsOnWishlistService($request);

         if(!$wishlist_was_edited)
         {
            throw new \Exception("Wishlist contents update failure!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'Wishlist Contents Changed Successfully!'
         ];

      }
      catch(\Exception $ex)
      {
         $status = [
            'code' => 0,
            'serverStatus' => 'WishlistUpdateFailure!',
            'short_description' => $ex->getMessage()
         ];

         return response()->json($status, 400);
      }
      /*finally
      {*/
         return response()->json($status, 200);
      //}

   }


   public function DeleteWishlist(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this->deleteWishlistRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Access Error, Not logged in yet!");
         }

         //this should return in chunks or paginate:
         $wishlist_was_deleted = $this->BuyerDeleteWishlistService($request);
         if(!$wishlist_was_deleted)
         {
            throw new \Exception("Wishlist not deleted successfully!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'WishlistDeletionSuccess!',
         ];
      }
      catch(\Exception $ex)
      {
         $status = [
            'code' => 0,
            'serverStatus' => 'WishlistDeletionFailure!',
            'short_description' => $ex->getMessage()
         ];
         return response()->json($status, 400);
      }
      /*finally
      {*/
         return response()->json($status, 200);
      //}
   }
   

   public function ConvertWishlistToCart(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this->convertWishlistToCartRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Invalid Input Provided!");
         }

         $wishlist_was_converted = $this->BuyerConvertWishlistToCartService($request);

         if(!$wishlist_was_converted)
         {
            throw new \Exception("Wishlist convertion to Cart failure!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'WishlistConversionSuccess!'
         ];

      }
      catch(\Exception $ex)
      {
         $status = [
            'code' => 0,
            'serverStatus' => 'WishlistConversionFailure!',
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
