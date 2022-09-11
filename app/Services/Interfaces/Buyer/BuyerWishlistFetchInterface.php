<?php
namespace App\Services\Interfaces\Buyer;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface BuyerWishlistFetchInterface 
{
    public function FetchAllWishlistsIdsOnly(Request $request): JsonResponse;
    public function FetchEachWishlistDetailByID(Request $request);
    public function FetchAllWishlistsDetails(Request $request): JsonResponse;
	public function FetchAllWishlistProductsIDsOnly(Request $request): JsonResponse;
}
    