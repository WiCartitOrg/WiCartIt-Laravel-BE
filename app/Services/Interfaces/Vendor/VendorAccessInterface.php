<?php
namespace App\Services\Interfaces\Vendor;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface VendorAccessInterface 
{
    public function Register(Request $request):JsonResponse;
	public function LoginDashboard(Request $request): JsonResponse;
    //public function ConfirmLoginState(Request $request): JsonResponse;
    public function ChangePassword(Request $request): JsonResponse;
    public function SendResetPassLink(Request $request): JsonResponse;
    public function ClickResetPasswordLink(string $reset_id, string $answer): JsonResponse;
    public function ImplementResetPassword(Request $request): JsonResponse;
    public function Logout(Request $request):  JsonResponse;
}

?>