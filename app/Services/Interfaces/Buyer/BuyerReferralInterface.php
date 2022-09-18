<?php
namespace App\Services\Interfaces\Buyer;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

interface BuyerReferralInterface 
{
    public function GenUniqueReferralLink(Request $request): JsonResponse;
	public function GetReferralBonus(Request $request): JsonResponse;
    public function FollowReferralLink(Request $request, $unique_buyer_id): JsonResponse;
}
    