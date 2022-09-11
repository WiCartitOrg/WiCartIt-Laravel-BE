<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

use App\Services\Interfaces\Vendor\VendorBusinessAndAccountInterface;

use App\Services\Traits\ModelAbstractions\Vendor\VendorBusinessAndAccountAbstraction;
use App\Validators\Vendor\VendorBusinessAndAccountRequestRules;

final class VendorBusinessAndAccountController extends Controller implements VendorBusinessAndAccountInterface
{
    use VendorBusinessAndAccountAbstraction;
    use VendorBusinessAndAccountRequestRules;
    
    public function __construct()
    {

    }

    //tested with get but not with put...
    public function UploadBankAccountDetails(Request $request): JsonResponse
    {
        $status = array();

        try
        {
            //get rules from validator class:
            $reqRules = $this->uploadBankAccountDetailsRules();

            //validate here:
            $validator = Validator::make($request->all(), $reqRules);

            if($validator->fails())
            {
                throw new \Exception("Invalid Inputs provided!");
            }

            //create without mass assignment:
            $detailsSaved = $this->VendorSaveBankDetailsService($request);
            if(!$detailsSaved/*false*/)
            {
                throw new \Exception("Bank Details not saved!");
            }

             $status = [
                'code' => 1,    
                'serverStatus' => 'bankDetailsSaved!',
            ];

        }
        catch(\Exception $ex)
        {
             $status = [
                'code' => 0,
                'serverStatus' => 'bankDetailsNotSaved!',
                'short_description' => $ex->getMessage(),
            ];
            return response()->json($status, 400);
        }
        //finally{
            return response()->json($status, 200);
        /*}*/
    }


    public function FetchEachBankAccountDetails(Request $request): JsonResponse
    {
        $status = array();

        try
        {
            //get rules from validator class:
            $reqRules = $this->fetchEachBankDetailsRules();

            //validate here:
            $validator = Validator::make($request->all(), $reqRules);

            if($validator->fails())
            {
                throw new \Exception("Access Error, not logged in yet!");
            }

            $fetchedDetails = $this->VendorFetchEachBankAccountDetailsService($request);
            
            if(empty($fetchedDetails))
            {
                throw new \Exception("Details Empty, please update to get values");
            }

            $status = [
                'code' => 1,
                'serverStatus' => 'FetchSuccess!',
                'bankDetails' => $fetchedDetails,
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
        }//finally{
            return response()->json($status, 200);
        //}
    }


    //tested with get but not with put...
    public function UploadBusinessDetails(Request $request): JsonResponse
    {
        $status = array();

        try
        {
            //get rules from validator class:
            $reqRules = $this->uploadBusinessDetailsRules();

            //validate here:
            $validator = Validator::make($request->all(), $reqRules);

            if($validator->fails())
            {
                throw new \Exception("Invalid Input provided!");
            }

            //create without mass assignment:
            $detailsSaved = $this->VendorUploadBusinessDetailsService($request);
            if(!$detailsSaved/*false*/)
            {
                throw new \Exception("Business Details not saved!");
            }

             $status = [
                'code' => 1,    
                'serverStatus' => 'bizDetailsSaved!',
                //'test' => $details_has_saved
            ];

        }
        catch(\Exception $ex)
        {

             $status = [
                'code' => 0,
                'serverStatus' => 'bizDetailsNotSaved!',
                'short_description' => $ex->getMessage()

            ];

        }
        //finally{
            return response()->json($status, 200);
        /*}*/
    }


    public function FetchEachBusinessDetails(Request $request): JsonResponse
    {
        $status = array();

        try{
            //get rules from validator class:
            $reqRules = $this->fetchBusinessDetailsRules();

            //validate here:
            $validator = Validator::make($request->all(), $reqRules);

            if($validator->fails())
            {
                throw new \Exception("Access Error, can't connect!");
            }

            $fetchedDetails = $this->VendorFetchBusinessDetailsService($request);
            
            if(empty($fetchedDetails))
            {
                throw new \Exception("Details Empty, please update to get values");
            }

            $status = [
                'code' => 1,
                'serverStatus' => 'FetchSuccess!',
                'bizDetails' => $fetchedDetails
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