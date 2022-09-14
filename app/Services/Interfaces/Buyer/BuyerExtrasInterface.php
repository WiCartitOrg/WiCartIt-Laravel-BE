<?php
namespace App\Services\Interfaces\Buyer;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface BuyerExtrasInterface 
{
    public function TrackProductsLocation(Request $request): JsonResponse;
	public function ConfirmProductsDelivery(Request $request): JsonResponse;
    public function CommentRateService(Request $request): JsonResponse;
    public function ViewOtherCommentsRates(Request $request): JsonResponse;
    public function FetchGeneralStatistics(Request $request): JsonResponse;
}
    