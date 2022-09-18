<?php
namespace App\Services\Interfaces\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

interface AdminReferralInterface 
{
	public function UpdateReferralDetails(Request $request): JsonResponse;
    public function FetchReferralDetails(Request $request): JsonResponse;
    public function DisableReferral(Request $request): JsonResponse;
    public function FetchGeneralStatistics(Request $request): JsonResponse;
}

?>