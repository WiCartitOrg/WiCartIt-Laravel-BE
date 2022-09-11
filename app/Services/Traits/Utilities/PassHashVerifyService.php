<?php

namespace App\Services\Traits\Utilities;

use Illuminate\Support\Facades\Hash;

trait PassHashVerifyService
{

	protected function DefaultHashPassword(string $password) : string
	{
		$passHash = password_hash($password, PASSWORD_DEFAULT);
		return $passHash;
	}


	protected function DefaultVerifyPassword(string $password, string $hash) : bool
	{
		$passVerify = password_verify($password, $hash);
		return $passVerify;
	}

	protected function CustomHashPassword(string $reqPass): string
    {
    	$firstPass = Hash::make($reqPass);
    	/*$secondPass = Hash::make($reqPass);
		$thirdPass = Hash::make($reqPass);
    	$finalHashedPass = Hash::make($firstPass . $secondPass . $thirdPass);*/

    	return $firstPass;
    }

	protected function CustomVerifyPassword(string $password, string $hash): bool
	{
		//then check equality:
		if(!Hash::check($password, $hash))
		{
			return false;
		}
		return true;
	}

}