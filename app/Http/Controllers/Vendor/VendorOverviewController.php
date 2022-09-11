<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

//use App\Services\Interfaces\AdminGeneralInterface;
use App\Services\Traits\ModelAbstraction\AdminGeneralAbstraction;
use App\Http\Controllers\Validators\AdminGeneralRequestRules;

final class AdminOverviewController extends Controller //implements AdminGeneralInterface
{
    public function GetSalesData(Request $request): JsonResponse
    {
        $status = array();

        try
        {
            //get rules from validator class:
            $reqRules = $this->getSalesDataRules();

            //validate here:
            $validator = Validator::make($request->all(), $reqRules);

            if($validator->fails()){
                throw new \Exception("Access Error, can't connect!");
            }

            $sales_data_found = $this->AdminGetSalesDataService();
            
            if(empty($sales_data_found))
            {
				throw new \Exception("Failed to retrieve sales data. Try Again!");
            }

            $status = [
                'code' => 1,
                'serverStatus' => 'RetrievalSuccess',
                'salesData' => $sales_data_found
            ];

        }catch(\Exception $ex){

            $status = [
                'code' => 0,
                'serverStatus' => 'RetrievalError',
                'short_description' => $ex->getMessage()
            ];

        }finally{
            return response()->json($status, 200);
        }
    }
    

    //View frequently bought goods:
    public function ViewFrequent(Request $request): JsonResponse
    {
         $status = array();

        try{

            //get rules from validator class:
            $reqRules = $this->viewFrequentRules();

            //validate here:'new_pass'
            $validator = Validator::make($request->all(), $reqRules);

            if($validator->fails()){
                throw new \Exception("Access Error, can't connect!");
            }

            //create without mass assignment:
            $frequentGoodsBoughtByMonth = $this->AdminViewFrequentService();
            if(empty($frequentGoodsBoughtByMonth))
            {
            	throw new \Exception("Frequently bought goods not found!");
            }

            $status = [
                'code' => 1,
                'serverStatus' => 'RetrievalSuccess',
                'frquentGoodsDetails' =>  $frequentGoodsBoughtByMonth,
            ];

        }catch(\Exception $ex){

            $status = [
                'code' => 0,
                'serverStatus' => 'RetrievalError',
                'short_description' => $ex->getMessage()
            ];

        }finally{
            return response()->json($status, 200);
        }
    }

}

?>