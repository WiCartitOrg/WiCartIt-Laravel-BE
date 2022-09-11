<?php
namespace App\Services\Interfaces\Vendor;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface VendorBusinessAndAccountInterface
{
	public function UploadBankAccountDetails(Request $request): JsonResponse;
	public function FetchEachBankAccountDetails(Request $request): JsonResponse;
	public function UploadBusinessDetails(Request $request): JsonResponse;
	public function FetchEachBusinessDetails(Request $request): JsonResponse;
}
