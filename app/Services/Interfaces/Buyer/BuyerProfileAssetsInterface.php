<?php
namespace App\Services\Interfaces\Buyer;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface BuyerProfileAssetsInterface {

    public function EditProfile(Request $request): JsonResponse;
    public function EditImage(Request $request): JsonResponse;
    
}

?>