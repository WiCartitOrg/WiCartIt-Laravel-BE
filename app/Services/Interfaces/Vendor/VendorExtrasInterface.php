<?php
namespace App\Services\Interfaces\Vendor;

interface EmployerExtrasInterface {

	public function outsourceRecruitment(): json;
	public function reportInterns(): json;
	public function genUniqueUrl(): json;

}

?>