<?php

namespace App\Services\Traits\Utilities;

use Illuminate\Support\Facades\Artisan;

use App\Services\Traits\ModelAbstractions\Admin\AdminAccessAbstraction;
use App\Services\Traits\Utilities\PassHashVerifyService;
use App\Services\Traits\Utilities\ComputeUniqueIDService;

trait AdminBossDefaultEntriesService
{
	use AdminAccessAbstraction;
	use ComputeUniqueIDService;
	use PassHashVerifyService;
	
	protected function CreateAdminBossDefaults()
	{
		Artisan::call('migrate:fresh');

		$createKeysValues = [
			'unique_admin_id' => $this->genUniqueAlphaNumID(),
			'admin_first_name' => env('ADMIN_BOSS_FIRST_NAME'),
			'admin_middle_name' => env('ADMIN_BOSS_MIDDLE_NAME'),
			'admin_last_name' => env('ADMIN_BOSS_LAST_NAME'),
			'admin_email' => env('ADMIN_BOSS_EMAIL'),
			'admin_password' => $this?->CustomHashPassword(env('ADMIN_BOSS_PASSWORD')),
			'admin_phone_number' => env('ADMIN_BOSS_PHONE_NUMBER'),
			'admin_role' => env('ADMIN_BOSS_ROLE'),
		];
		$is_admin_saved = $this?->AdminCreateAllService($createKeysValues);
		return $is_admin_saved;
	}

}		