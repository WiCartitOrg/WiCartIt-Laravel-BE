<?php
namespace App\Services\Interfaces\Buyer;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface BuyerCartFetchInterface 
{
    public function FetchCartsByCategory(Request $request): JsonResponse;
    public function FetchCartsIDsOnly(Request $request): JsonResponse;
	public function FetchAllCartProductsIDsOnly(Request $request): JsonResponse;
}
    