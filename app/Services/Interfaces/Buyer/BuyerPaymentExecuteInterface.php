<?php
namespace App\Services\Interfaces\Buyer;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface BuyerPaymentExecuteInterface 
{
	public function MakePaymentWithNewCard(Request $request): JsonResponse;
	public function MakePaymentWithSavedCard(Request $request): JsonResponse;
	public function MakePaymentWithNewBank(Request $request): JsonResponse;
	public function MakePaymentWithSavedBank(Request $request): JsonResponse;
}

?>