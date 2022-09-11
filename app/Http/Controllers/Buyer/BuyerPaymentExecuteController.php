<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Validators\BuyerPaymentExecuteRequestRules;

//use App\Services\Interfaces\BuyerCartInterface;
use App\Services\Traits\ModelAbstraction\BuyerPaymentExecuteAbstraction;

final class BuyerPaymentExecuteController extends Controller //implements BuyerCartInterface, PaymentInterface
{
   use BuyerPaymentExecuteAbstraction;
   use BuyerPaymentExecuteRequestRules;

   public function __construct()
   {
        //$this->createBuyerDefault();
   }

   //use guzzlehttp to connect to external API to make payment:

   //first display the summary of all pending(not paid yet) or cleared cart(paid)
   public function MakePayment(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this->fetchMakePaymentRules();

         //validate here:
         $validator = Validator::make($request->all(), $reqRules);

         if($validator->fails())
         {
            throw new \Exception("Access Error, Not logged in yet!");
         }
         
         //this should return in chunks or paginate:
         $paymentMadeDetails = $this->BuyerMakePaymentService($request);
         if( empty($paymentMadeDetails) )
         {
            throw new \Exception("Error! Payment transaction unsuccessful!");
         }

         if(!$paymentMadeDetails['is_payment_made'])
         {
            throw new \Exception("Error! Payment transaction unsuccessful!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'TransactionSuccess!',
            'transDetails' => $paymentMadeDetails
         ];

      }
      catch(\Exception $ex)
      {

         $status = [
            'code' => 0,
            'serverStatus' => 'TransactionError!',
            'short_description' => $ex->getMessage()
         ];

      }
      /*finally
      {*/
         return response()->json($status, 200);
      //}
   }
}
