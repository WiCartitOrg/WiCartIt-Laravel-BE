<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

use App\Validators\Buyer\BuyerProfileAssetsRequestRules;
use App\Services\Interfaces\Buyer\BuyerProfileAssetsInterface;

final class BuyerProfileAssetsController extends Controller implements BuyerProfileAssetsInterface
{
    use BuyerProfileAssetsRequestRules;

     //this might include payment details of this user..
    public function EditProfile(Request $request): JsonResponse
    {
         $status = array();     

        try
        {
            //get rules from validator class:
            $reqRules = $this->editRules();

            //validate here:'new_pass'
            $validator = Validator::make($request->all(), $reqRules);

            if($validator->fails())
            {
                throw new \Exception("Invalid Input provided!");
            }

            //create without mass assignment:
            $is_details_saved = $this->BuyerUpdateEachService($request);
            if(!$is_details_saved/*false*/)
            {
                throw new \Exception("Details not saved!");
            }

            $status = [
                'code' => 1,
                'serverStatus' => 'ProfileUpdateSuccess!',
            ];

        }
        catch(\Exception $ex)
        {
            $status = [
                'code' => 0,
                'serverStatus' => 'ProfileUpdateFailure!',
                'short_description' => $ex->getMessage()
            ];
        }
        finally
        {
            return response()->json($status, 200);
        }
    }


    //buyers can update their images if they so wish
    public function EditImage(Request $request):  JsonResponse
    {
        $status = array();

        try{

            //get rules from validator class:
            $reqRules = $this->imagesRules();

            //validate here:
            $validator = Validator::make($request->all(), $reqRules);

            if($validator->fails())
            {
                throw new \Exception("Invalid Input provided!");
            }

            //create without mass assignment:
            $files_has_saved = $this->BuyerSaveFilesService($request);
            if(!$files_has_saved/*false*/)
            {
                throw new \Exception("File Details not saved!");
            }

             $status = [
                'code' => 1,
                'serverStatus' => 'ImageUpdateSuccess!',
                //'requestLists' => $request->all()
            ];

        }
        catch(\Exception $ex)
        {
             $status = [
                'code' => 0,
                'serverStatus' => 'ImageUpdateFailure!',
                'short_description' => $ex->getMessage()
            ];
        }
        finally
        {
            return response()->json($status, 200);
        }

    }


}