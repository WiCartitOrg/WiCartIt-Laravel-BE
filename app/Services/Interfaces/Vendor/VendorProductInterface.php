<?php
namespace App\Services\Interfaces\Vendor;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface VendorProductInterface
{
	public function UploadProductTextDetails(Request $request): JsonResponse;
	public function UploadProductImageDetails(Request $request):  JsonResponse;
    public function FetchAllProductSummary(Request $request): JsonResponse;
    public function FetchEachProductDetails(Request $request): JsonResponse;
    public function  DeleteEachProductDetails(Request $request): JsonResponse;
}
