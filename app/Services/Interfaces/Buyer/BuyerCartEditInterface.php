<?php
namespace App\Services\Interfaces\Buyer;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface BuyerCartEditInterface 
{
    public function AddProductsToPendingCart(Request $request): JsonResponse;
	public function EditProductsOnPendingCart(Request $request): JsonResponse;
    public function DeletePendingCart(Request $request): JsonResponse;
}
    