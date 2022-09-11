<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

use App\Services\Interfaces\Vendor\VendorProductInterface;
use App\Services\Traits\ModelAbstractions\Vendor\VendorProductAbstraction;
use App\Validators\Vendor\VendorProductRequestRules;

final class VendorProductController extends Controller implements VendorProductInterface
{
    use VendorProductAbstraction;
    use VendorProductRequestRules;
    
    public function __construct()
    {

    }

    public function UploadProductTextDetails(Request $request): JsonResponse
    {
        $status = array();

        try
        {
            //get rules from validator class:
            $reqRules = $this->uploadProductTextDetailsRules();

            //validate here:
            $validator = Validator::make($request->all(), $reqRules);

            if($validator->fails())
            {
                throw new \Exception("Invalid Input provided!");
            }

            //create without mass assignment:
            $details_saved = $this->VendorSaveProductDetailsService($request);
            if(!($details_saved['is_saved']))
            {
                throw new \Exception("Product Details not saved!");
            }

             $status = [
                'code' => 1,    
                'serverStatus' => 'ProductTextDetailsSaved!',
                'product_token_id' => $details_saved['product_token_id']
            ];

        }
        catch(\Exception $ex)
        {
            $status = [
                'code' => 0,
                'serverStatus' => 'ProductTextDetailsNotSaved!',
                'short_description' => $ex->getMessage()
            ];
            return response()->json($status, 400);
        }
        //finally{
            return response()->json($status, 206);
        /*}*/
    }

     //buyers can update their images if they so wish
    public function UploadProductImageDetails(Request $request):  JsonResponse
    {
        $status = array();

        try
        {
            //get rules from validator class:
            $reqRules = $this->uploadProductImageDetailsRules();

            //validate here:
            $validator = Validator::make($request->all(), $reqRules);

            if($validator->fails())
            {
                throw new \Exception("Invalid Input provided!");
            }

            //create without mass assignment:
            $files_has_saved = $this->VendorSaveProductImageDetailsService($request);
            if(!$files_has_saved/*false*/)
            {
                throw new \Exception("Product Image not saved!");
            }

            $status = [
                'code' => 1,
                'serverStatus' => 'imageDetailsSaved!',
                'requestLists' => $request->file('main_image_1')
            ];

        }
        catch(\Exception $ex)
        {
             $status = [
                'code' => 0,
                'serverStatus' => 'imageDetailsNotSaved!',
                'short_description' => $ex->getMessage()
            ];

            return response()->json($status, 400);
        }
        //finally{
            return response()->json($status, 200);
        //}

    }


    public function FetchAllProductSummary(Request $request): JsonResponse
    {
        $status = array();

        try
        {
            //get rules from validator class:
            $reqRules = $this->fetchAllProductSummaryRules();

            //validate here:
            $validator = Validator::make($request->all(), $reqRules);

            if($validator->fails())
            {
                throw new \Exception("Access Error, can't connect!");
            }

            $allProductSummary = $this->VendorFetchAllProductSummaryService($request);
            
            if(empty($allProductSummary))
            {
                throw new \Exception("Product Summary empty, create a new one!");
            }

            $status = [
                'code' => 1,
                'serverStatus' => 'FetchSuccess!',
                'productDetails' => $allProductSummary->toJson(),
            ];

        }
        catch(\Exception $ex)
        {
            $status = [
                'code' => 0,
                'serverStatus' => 'FetchError!',
                'short_description' => $ex->getMessage()
            ];

        }//finally{
            return response()->json($status, 200);
        //}
    }


    public function FetchEachProductDetails(Request $request): JsonResponse
    {
      $status = array();

        try
        {
            //get rules from validator class:
            $reqRules = $this->fetchEachProductDetailsRules();

            //validate here:
            $validator = Validator::make($request->all(), $reqRules);

            if($validator->fails())
            {
                throw new \Exception("Invalid Product ID provided!");
            }
         
            //this should return in chunks or paginate in buyer:
            $detailsFound = $this->VendorFetchEachProductDetailsService($request);
            if( empty($detailsFound) )
            {
                throw new \Exception("Product Details not found! Ensure to create a new Product!");
            }

            $status = [
                'code' => 1,
                'serverStatus' => 'FetchSuccess!',
                'productDetails' => $detailsFound
            ];

        }
        catch(\Exception $ex)
        {
            $status = [
                'code' => 0,
                'serverStatus' => 'FetchError!',
                'short_description' => $ex->getMessage()
            ];
        }
        /*finally
        {*/
            return response()->json($status, 200);
        //}
    }


    public function  DeleteEachProductDetails(Request $request): JsonResponse
    {
        $status = array();
        try
        {
            //get rules from validator class:
            $reqRules = $this->deleteEachProductDetailsRules();

            //validate here:
            $validator = Validator::make($request->all(), $reqRules);

            if($validator->fails())
            {
                throw new \Exception("Invalid Product ID provided!");
            }
         
            //this should return in chunks or paginate:
            $productHasDeleted = $this->VendorDeleteEachProductDetailsService($request);
            if(!$productHasDeleted)
            {
                throw new \Exception("Product not yet deleted, please try again!.");
            }

            $status = [
                'code' => 1,
                'serverStatus' => 'DeleteSuccess!',
            ];

        }
        catch(\Exception $ex)
        {
            $status = [
                'code' => 0,
                'serverStatus' => 'DeleteError!',
                'short_description' => $ex->getMessage()
            ];
        }
        /*finally
        {*/
            return response()->json($status, 200);
        //}
    }

}

?>
