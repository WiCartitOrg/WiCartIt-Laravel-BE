<?php
namespace App\Services\Interfaces\Vendor;

interface EmployerInterface {

	public function searchEmployers():json;
	public function searchInterns():json;

	public function recommend_rateInterns():json;
	public function chatInterns():json;
	
	public function jobPost():json;
	public function jobPostsViewAll():json;
	public function jobPostsChange(): json;
	public function jobPostsDelete(): json;
	public function jobPostsDelay(): json;

	public function outsourceRecruitment(): json;
	public function reportInterns(): json;
	public function genUniqueUrl(): json;

}

?>