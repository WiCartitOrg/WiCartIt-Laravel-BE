<?php
namespace App\Services\Interfaces\Vendor;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface VendorWishlistFetchInterface 
{
    public function FetchRelatedWishlistProductsDetails(Request $request): JsonResponse;
    public function FetchWishingBuyersDetails(Request $request): JsonResponse;
}
    