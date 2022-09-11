<?php
namespace App\Services\Interfaces\Vendor;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface VendorProfileAssetsInterface {

    public function EditProfile(Request $request): JsonResponse;
    public function EditImage(Request $request): JsonResponse;
    
}

?>