<?php
namespace App\Services\Interfaces\Buyer;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface BuyerBillingAndShippingInterface 
{
    public function UploadBillingDetails(Request $request): JsonResponse;
    public function FetchBillingDetails(Request $request): JsonResponse;
    public function UploadShippingDetails(Request $request): JsonResponse;
    public function FetchShippingDetails(Request $request): JsonResponse;
    
}