<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

//use App\Services\Interfaces\VendorGeneralInterface;
use App\Services\Traits\ModelAbstraction\VendorGeneralAbstraction;
use App\Http\Controllers\Validators\VendorGeneralRequestRules;

final class VendorOverviewController extends Controller //implements VendorGeneralInterface
{
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
                throw new \Exception("Access Error, can't connect!");
            }

            $sales_data_found = $this?->VendorFetchGeneralStatisticsService();
            
            if(empty($sales_data_found))
            {
				throw new \Exception("Failed to retrieve sales data. Try Again!");
            }

            $status = [
                'code' => 1,
                'serverStatus' => 'RetrievalSuccess',
                'salesData' => $sales_data_found
            ];
        }
        catch(\Exception $ex)
        {
            $status = [
                'code' => 0,
                'serverStatus' => 'RetrievalError',
                'short_description' => $ex?->getMessage()
            ];
            return response()?->json($status, 400);
        }
        /*finally
        {*/
            return response()?->json($status, 200);
        //}
    }

    public function GetSalesData(Request $request): JsonResponse
    {
        $status = array();

        try
        {
            //get rules from validator class:
            $reqRules = $this?->getSalesDataRules();

            //validate here:
            $validator = Validator::make($request?->all(), $reqRules);

            if($validator?->fails())
            {
                throw new \Exception("Access Error, can't connect!");
            }

            $sales_data_found = $this?->VendorGetSalesDataService();
            
            if(empty($sales_data_found))
            {
				throw new \Exception("Failed to retrieve sales data. Try Again!");
            }

            $status = [
                'code' => 1,
                'serverStatus' => 'RetrievalSuccess',
                'salesData' => $sales_data_found
            ];
        }
        catch(\Exception $ex)
        {
            $status = [
                'code' => 0,
                'serverStatus' => 'RetrievalError',
                'short_description' => $ex?->getMessage()
            ];
            return response()?->json($status, 400);
        }
        /*finally
        {*/
            return response()?->json($status, 200);
        //}
    }
    

    //View frequently bought goods:
    public function ViewFrequent(Request $request): JsonResponse
    {
         $status = array();

        try
        {
            //get rules from validator class:
            $reqRules = $this?->viewFrequentRules();

            //validate here:'new_pass'
            $validator = Validator::make($request?->all(), $reqRules);

            if($validator?->fails())
            {
                throw new \Exception("Access Error, can't connect!");
            }

            //create without mass assignment:
            $frequentGoodsBoughtByMonth = $this?->VendorViewFrequentService();
            if(empty($frequentGoodsBoughtByMonth))
            {
            	throw new \Exception("Frequently bought goods not found!");
            }

            $status = [
                'code' => 1,
                'serverStatus' => 'RetrievalSuccess',
                'frquentGoodsDetails' =>  $frequentGoodsBoughtByMonth,
            ];
        }
        catch(\Exception $ex)
        {
            $status = [
                'code' => 0,
                'serverStatus' => 'RetrievalError',
                'short_description' => $ex?->getMessage()
            ];
            return response()?->json($status, 400);
        }
        /*finally
        {*/
            return response()?->json($status, 200);
        //}
    }

}

?>