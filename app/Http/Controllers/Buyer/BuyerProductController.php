<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

use App\Services\Traits\ModelAbstractions\Buyer\BuyerProductAbstraction;
use App\Services\Interfaces\Buyer\BuyerProductInterface;
use App\Validators\Buyer\BuyerProductRequestRules;

use App\Services\Traits\Utilities\PaginateCustomCollection;


final class BuyerProductController extends Controller implements BuyerProductInterface
{
   use BuyerProductAbstraction;
   use BuyerProductRequestRules;
   use PaginateCustomCollection;

   public function __construct()
   {
        //$this->createAdminDefault();
   }

   public function FetchAllProducts(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         /*$reqRules = $this->fetchAvailableProductsRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Invalid Input provided!");
         }  
         */
         //this should return in chunks or paginate:
         $detailsFound = $this->BuyerFetchAvailableProductsService();
         if( empty($detailsFound) )
         {
            throw new \Exception("Products Not Found!");
         }

         //Arrange pagination through new collection:
         $paginateProducts = $this->Paginate(collect($detailsFound), 10);

         $status = [
            'code' => 1,
            'serverStatus' => 'FetchSuccess!',
            'totalCount' => count($detailsFound),
            'products' => $paginateProducts->toJson(),
            'links' => $paginateProducts->links(),
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

      }/*finally
      {*/
         //$status = mb_convert_encoding($status, "UTF-8", "auto");
         return response()->json($status, 200);
      //}

   }


   public function FetchProductsByCategory(string $category): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         /*$reqRules = $this->fetchProductsByCategoryRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Invalid Input provided!");
         }  
         */
         //this should return in chunks or paginate:
         $detailsFound = $this->BuyerFetchProductsByCategoryService($category);
         if( empty($detailsFound) )
         {
            throw new \Exception("Products Not Found!");
         }

         //Arrange pagination through new collection:
         $paginateProducts = $this->Paginate(collect($detailsFound), 10);

         $status = [
            'code' => 1,
            'serverStatus' => 'FetchSuccess!',
            'totalCount' => count($detailsFound),
            'products' => $paginateProducts->toJson(),
            'links' => $paginateProducts->links(),  
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

      }/*finally
      {*/
         //$status = mb_convert_encoding($status, "UTF-8", "auto");
         return response()->json($status, 200);
      //}
   }


   //for logged in buyers:
   public function FetchAllProductsSummary(Request $request):JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this->fetchAvailableProductsRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Invalid Input provided!");
         }  
         
         //this should return in chunks or paginate:
         $detailsFound = $this->BuyerFetchAllProductSummaryService();
         if( empty($detailsFound))
         {
            throw new \Exception("Products Not Found!");
         }

         //Arrange pagination through new collection:
         $paginateProducts = $this->Paginate(collect($detailsFound), 10);

         $status = [
            'code' => 1,
            'serverStatus' => 'FetchSuccess!',
            'totalCount' => count($detailsFound),
            'products' => $paginateProducts->toJson(),
            'links' => $paginateProducts->links(),
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

      }/*finally
      {*/
         //$status = mb_convert_encoding($status, "UTF-8", "auto");
         return response()->json($status, 200);
      //}

   }


   //for logged in buyers:
   public function FetchSpecificProductWithVendorDetails(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this->fetchSpecificProductWithVendorDetailsRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Invalid Input provided!");
         }  
         
         //this should return in chunks or paginate:
         $detailsFound = $this->BuyerFetchSpecificProductWithVendorService($request);
         if( empty($detailsFound) )
         {
            throw new \Exception("Products Not Found!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'FetchSuccess!',
            'product&vendor_details' => $detailsFound,
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

      }/*finally
      {*/
         //$status = mb_convert_encoding($status, "UTF-8", "auto");
         return response()->json($status, 200);
      //}

   }

}