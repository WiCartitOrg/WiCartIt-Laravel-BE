<?php
namespace App\Services\Interfaces\Buyer;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

interface BuyerReferralInterface 
{
    public function GenUniqueReferralLink(Request $request): JsonResponse;
	public function ReferralBonus(Request $request): JsonResponse;
    public function ReferralLinkUse(Request $request, $unique_buyer_id): RedirectResponse;
}
    