<?php
namespace App\Services\Interfaces\Buyer;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface BuyerWishlistEditInterface 
{
    public function AddProductsToWishlist(Request $request): JsonResponse;
	public function EditProductsOnWishlist(Request $request): JsonResponse;
    public function DeleteWishlist(Request $request): JsonResponse;
    public function ConvertWishlistToCart(Request $request): JsonResponse;
}
    