<?php
namespace App\Services\Interfaces\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

interface AdminAccessInterface 
{
	public function LoginDashboard(Request $request): JsonResponse;
	public function ChangePassword(Request $request): JsonResponse;
	public function SendResetPassLink(Request $request): JsonResponse;
	public function ClickResetPasswordLink(string $reset, string $answer): JsonResponse;
	public function ImplementResetPassword(Request $request): JsonResponse;
	public function Logout(Request $request):  JsonResponse;
}

?>