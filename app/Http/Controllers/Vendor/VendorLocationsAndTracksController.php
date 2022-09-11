<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

//use App\Services\Interfaces\AdminGeneralInterface;
use App\Services\Traits\ModelAbstraction\AdminLocationsAndTracksAbstraction;
use App\Http\Controllers\Validators\AdminLocationsAndTracksRequestRules;

final class AdminLocationsAndTracksController extends Controller //implements AdminBankAndPaymentInterface
{
    use AdminLocationsAndTracksAbstraction;
    use AdminLocationsAndTracksRequestRules;
    
    public function __construct()
    {

    }

    //tested with get but not with put...
    public function UpdateLocationDetails(Request $request): JsonResponse
    {
        $status = array();

        try
        {
            //get rules from validator class:
            $reqRules = $this->updateLocationDetailsRules();

            //validate here:
            $validator = Validator::make($request->all(), $reqRules);

            if($validator->fails())
            {
                throw new \Exception("Incorrect Cart ID!");
            }

            //create without mass assignment:
            $details_has_saved = $this->AdminSaveLocationsAndTracksService($request);
            if(!$details_has_saved/*false*/)
            {
                throw new \Exception("Location Details not saved! Ensure that this is not a pending cart!");
            }

             $status = [
                'code' => 1,    
                'serverStatus' => 'locationDetailsSaved!',
            ];

        }
        catch(\Exception $ex)
        {

             $status = [
                'code' => 0,
                'serverStatus' => 'locationDetailsNotSaved!',
                'short_description' => $ex->getMessage(),
            ];

        }
        //finally{
            return response()->json($status, 200);
        /*}*/
    }

    public function FetchLocationDetails(Request $request): JsonResponse
    {
        $status = array();

        try{
            //get rules from validator class:
            $reqRules = $this->fetchLocationDetailsRules();

            //validate here:
            $validator = Validator::make($request->all(), $reqRules);

            if($validator->fails())
            {
                throw new \Exception("Incorrect Cart ID!");
            }

            $location_details_fetched = $this->AdminFetchLocationsAndTracksService($request);
            
            if(empty($location_details_fetched))
            {
                throw new \Exception("Details Empty, please update to get values");
            }

            $status = [
                'code' => 1,
                'serverStatus' => 'FetchSuccess!',
                'locationDetails' => $location_details_fetched
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
