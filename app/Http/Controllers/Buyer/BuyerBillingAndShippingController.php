<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

use App\Services\Interfaces\Buyer\BuyerBillingAndShippingInterface;
use App\Services\Traits\ModelAbstractions\Buyer\BuyerBillingAndShippingAbstraction;
use App\Validators\Buyer\BuyerBillingAndShippingRequestRules;

final class BuyerBillingAndShippingController extends Controller implements BuyerBillingAndShippingInterface
{
    use BuyerBillingAndShippingRequestRules;
    use BuyerBillingAndShippingAbstraction;
    
    public function __construct()
    {

    }

    //tested with get but not with put...
    public function UploadBillingDetails(Request $request): JsonResponse
    {
        $status = array();

        try
        {
            //get rules from validator class:
            $reqRules = $this?->uploadBillingDetailsRules();

            //validate here:
            $validator = Validator::make($request?->all(), $reqRules);

            if($validator?->fails())
            {
                throw new \Exception("Invalid Inputs provided!");
            }

            //create without mass assignment:
            $details_has_saved = $this?->BuyerSaveBillingDetailsService($request);
            if(!$details_has_saved/*false*/)
            {
                throw new \Exception("Billing Details not saved!");
            }

             $status = [
                'code' => 1,    
                'serverStatus' => 'DetailsSaved!',
            ];

        }
        catch(\Exception $ex)
        {
             $status = [
                'code' => 0,
                'serverStatus' => 'DetailsNotSaved!',
                'short_description' => $ex?->getMessage(),
            ];
            return response()?->json($status, 400);
        }
        //finally{
            return response()?->json($status, 200);
        /*}*/
    }


    public function FetchBillingDetails(Request $request): JsonResponse
    {
        $status = array();

        try
        {
            //get rules from validator class:
            $reqRules = $this?->fetchBillingDetailsRules();

            //validate here:
            $validator = Validator::make($request?->all(), $reqRules);

            if($validator?->fails())
            {
                throw new \Exception("Access Error, not a logged-in user!");
            }

            $billingDetailObject = $this?->BuyerFetchBillingDetailsService($request);
            
            if(!$billingDetailObject)
            {
                throw new \Exception("Details Empty, please update to get values");
            }

            $status = [
                'code' => 1,
                'serverStatus' => 'FetchSuccess!',
                'billingDetails' => $billingDetailObject
            ];

        }
        catch(\Exception $ex)
        {
            $status = [
                'code' => 0,
                'serverStatus' => 'FetchError!',
                'short_description' => $ex?->getMessage()
            ];
            return response()?->json($status, 400);
        }
        /*finally
        {*/
            return response()?->json($status, 200);
        //}
    }


    //tested with get but not with put...
    public function UploadShippingDetails(Request $request): JsonResponse
    {
        $status = array();

        try
        {
            //get rules from validator class:
            $reqRules = $this?->uploadShippingDetailsRules();

            //validate here:
            $validator = Validator::make($request?->all(), $reqRules);

            if($validator?->fails())
            {
                throw new \Exception("Invalid Inputs provided!");
            }

            //create without mass assignment:
            $details_has_saved = $this?->BuyerSaveShippingDetailsService($request);
            if(!$details_has_saved/*false*/)
            {
                throw new \Exception("Shipping Details not saved!");
            }

             $status = [
                'code' => 1,    
                'serverStatus' => 'DetailsSaved!',
            ];

        }
        catch(\Exception $ex)
        {
            $status = [
                'code' => 0,
                'serverStatus' => 'DetailsNotSaved!',
                'short_description' => $ex?->getMessage(),
            ];
            return response()?->json($status, 400);
        }
        //finally{
            return response()?->json($status, 200);
        /*}*/
    }


    public function FetchShippingDetails(Request $request): JsonResponse
    {
        $status = array();

        try
        {
            //get rules from validator class:
            $reqRules = $this?->fetchShippingDetailsRules();

            //validate here:
            $validator = Validator::make($request?->all(), $reqRules);

            if($validator?->fails())
            {
                throw new \Exception("Access Error, not a logged-in user!");
            }

            $shippingDetailObject = $this?->BuyerFetchShippingDetailsService($request);
            
            if(!$shippingDetailObject)
            {
                throw new \Exception("Details Empty, please update to get values");
            }

            $status = [
                'code' => 1,
                'serverStatus' => 'FetchSuccess!',
                'shippingDetails' => $shippingDetailObject
            ];

        }
        catch(\Exception $ex)
        {
            $status = [
                'code' => 0,
                'serverStatus' => 'FetchError!',
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
