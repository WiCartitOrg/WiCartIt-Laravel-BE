<?php
namespace App\Services\Interfaces\Buyer;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface BuyerProductInterface 
{
    public function FetchAllProducts(Request $request): JsonResponse;
    public function FetchProductsByCategory(string $request): JsonResponse;
    public function FetchAllProductsSummary(Request $request):JsonResponse;
    public function FetchSpecificProductWithVendorDetails(Request $request): JsonResponse;
}

?>