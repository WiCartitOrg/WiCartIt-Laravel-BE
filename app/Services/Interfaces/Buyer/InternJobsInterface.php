<?php
namespace App\Services\Interfaces\Buyer;

use Illuminate\Http\Request;

interface InternJobsInterface {

	public function jobApply():json;
	public function jobSearch():json;
	public function jobApplyAll(): json;

}