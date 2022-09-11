<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

//use App\Services\Interfaces\AdminGeneralInterface;
use App\Services\Traits\ModelAbstraction\AdminBankAndPaymentAbstraction;
use App\Http\Controllers\Validators\AdminBankAndPaymentRequestRules;

final class AdminBankAndPaymentController extends Controller //implements AdminBankAndPaymentInterface
{
    use AdminBankAndPaymentRequestRules;
    use AdminBankAndPaymentAbstraction;
    
    public function __construct()
    {

    }

    //tested with get but not with put...
    public function UpdateBankDetails(Request $request): JsonResponse
    {
        $status = array();

        try
        {
            //get rules from validator class:
            $reqRules = $this->updateBankDetailsRules();

            //validate here:
            $validator = Validator::make($request->all(), $reqRules);

            if($validator->fails())
            {
                throw new \Exception("Invalid Input provided!");
            }

            //create without mass assignment:
            $details_has_saved = $this->AdminSaveBankDetailsService($request);
            if(!$details_has_saved/*false*/)
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

        }
        //finally{
            return response()->json($status, 200);
        /*}*/
    }

    public function FetchBankDetails(Request $request): JsonResponse
    {
        $status = array();

        try{
            //get rules from validator class:
            $reqRules = $this->fetchBankDetailsRules();

            //validate here:
            $validator = Validator::make($request->all(), $reqRules);

            if($validator->fails())
            {
                throw new \Exception("Access Error, can't connect!");
            }

            $biz_details_fetched = $this->AdminFetchBankDetailsService($request);
            
            if(empty($biz_details_fetched))
            {
                throw new \Exception("Details Empty, please update to get values");
            }

            $status = [
                'code' => 1,
                'serverStatus' => 'FetchSuccess!',
                'bankDetails' => $biz_details_fetched
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
}

?>
