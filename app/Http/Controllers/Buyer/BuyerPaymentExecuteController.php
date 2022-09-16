<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

use App\Validators\Buyer\BuyerPaymentExecuteRequestRules;

use App\Services\Interfaces\Buyer\BuyerPaymentExecuteInterface;
use App\Services\Traits\ModelAbstractions\Buyer\BuyerPaymentExecuteAbstraction;

final class BuyerPaymentExecuteController extends Controller implements BuyerPaymentExecuteInterface
{
   use BuyerPaymentExecuteAbstraction;
   use BuyerPaymentExecuteRequestRules;

   public function __construct()
   {
        //$this?->createBuyerDefault();
   }

   //use guzzlehttp to connect to external API to make payment:
   public function MakePaymentWithNewCard(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this?->makePaymentWithNewCardRules();

         //validate here:
         $validator = Validator::make($request?->all(), $reqRules);

         if($validator?->fails())
         {
            throw new \Exception("Invalid Input Provided!");
         }
         
         //connect to external API here:
         $paymentWasMadeWithDetails = $this?->BuyerMakePaymentWithNewCardService($request);

         if(!$paymentWasMadeWithDetails)
         {
            throw new \Exception("Payment transaction failure!");
         }

         if( empty($paymentWasMadeWithDetails) )
         {
            throw new \Exception("Payment transaction failure!");
         }

         if(!$paymentWasMadeWithDetails['payment_was_made'])
         {
            throw new \Exception("Payment transaction failure!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'PaymentTransactionSuccess!',
            'transactionDetails' => $paymentWasMadeWithDetails,
         ];
      }
      catch(\Exception $ex)
      {
         $status = [
            'code' => 0,
            'serverStatus' => 'PaymentTransactionFailure!',
            'short_description' => $ex?->getMessage()
         ];
         return response()?->json($status, 400);
      }
      /*finally
      {*/
         return response()?->json($status, 200);
      //}
   }


   public function MakePaymentWithSavedCard(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this?->makePaymentWithSavedCardRules();

         //validate here:
         $validator = Validator::make($request?->all(), $reqRules);

         if($validator?->fails())
         {
            throw new \Exception("Access Error, Not logged in yet!");
         }
         
         //this should return in chunks or paginate:
         $paymentMadeDetails = $this?->BuyerMakePaymentWithSavedCardService($request);

         if( empty($paymentMadeDetails) )
         {
            throw new \Exception("Payment transaction unsuccessful!");
         }

         if(!$paymentMadeDetails['payment_was_made'])
         {
            throw new \Exception("Payment transaction unsuccessful!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'PaymentTransactionSuccess!',
            'transDetails' => $paymentMadeDetails
         ];

      }
      catch(\Exception $ex)
      {
         $status = [
            'code' => 0,
            'serverStatus' => 'PaymentTransactionFailure!',
            'short_description' => $ex?->getMessage()
         ];
         return response()?->json($status, 400);
      }
      /*finally
      {*/
         return response()?->json($status, 200);
      //}
   }
   

   public function MakePaymentWithNewBank(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this?->makePaymentWithNewBankRules();

         //validate here:
         $validator = Validator::make($request?->all(), $reqRules);

         if($validator?->fails())
         {
            throw new \Exception("Access Failure, Not logged in yet!");
         }
         
         //this should return in chunks or paginate:
         $paymentMadeDetailsWithDetails = $this?->BuyerMakePaymentWithNewBankService($request);

         if(!$paymentWasMadeWithDetails)
         {
            throw new \Exception("Payment transaction failure!");
         }

         if( empty($paymentWasMadeWithDetails) )
         {
            throw new \Exception("Payment transaction failure!");
         }

         if(!$paymentWasMadeWithDetails['payment_was_made'])
         {
            throw new \Exception("Payment transaction failure!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'PaymentTransactionSuccess!',
            'transactionDetails' => $paymentWasMadeWithDetails,
         ];
      }
      catch(\Exception $ex)
      {
         $status = [
            'code' => 0,
            'serverStatus' => 'PaymentTransactionFailure!',
            'short_description' => $ex?->getMessage()
         ];
         return response()?->json($status, 400);
      }
      /*finally
      {*/
         return response()?->json($status, 200);
      //}
   }


   public function MakePaymentWithSavedBank(Request $request): JsonResponse
   {
      $status = array();

      try
      {
         //get rules from validator class:
         $reqRules = $this?->makePaymentWithSavedBankRules();

         //validate here:
         $validator = Validator::make($request?->all(), $reqRules);

         if($validator?->fails())
         {
            throw new \Exception("Invalid Input Provided!");
         }
         
         //this should return in chunks or paginate:
         $paymentWasMadeWithDetails = $this?->BuyerMakePaymentWithSavedBankService($request);

         if(!$paymentWasMadeWithDetails)
         {
            throw new \Exception("Payment transaction failure!");
         }

         if( empty($paymentWasMadeWithDetails) )
         {
            throw new \Exception("Payment transaction failure!");
         }

         if(!$paymentWasMadeWithDetails['payment_was_made'])
         {
            throw new \Exception("Payment transaction failure!");
         }

         $status = [
            'code' => 1,
            'serverStatus' => 'PaymentTransactionSuccess!',
            'transactionDetails' => $paymentWasMadeWithDetails,
         ];
      }
      catch(\Exception $ex)
      {
         $status = [
            'code' => 0,
            'serverStatus' => 'PaymentTransactionFailure!',
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
