<?php
namespace App\Services\Interfaces\Vendor;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface VendorCartFetchInterface 
{
    public function FetchRelatedCartProductsDetails(Request $request): JsonResponse;
    public function FetchBuyersCustomerDetails(Request $request);
}
    