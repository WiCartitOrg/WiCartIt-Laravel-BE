<?php
namespace App\Services\Interfaces\Buyer;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface BuyerPaymentDetailsInterface 
{
	public function UploadCardDetails(Request $request): JsonResponse;
	public function FetchEachCardDetails(Request $request): JsonResponse;
	public function UploadAccountDetails(Request $request): JsonResponse;
	public function FetchEachAccountDetails(Request $request): JsonResponse;
}

?>