<?php
namespace App\Services\Interfaces\Vendor;

interface EmployerJobsInterface {

	public function PostJobs($request);
	public function ChangeJobPosted($request);
	public function ViewAllJobPosts($request);
	public function DeleteJobPost($request);
	public function DelayJob($request);

	/*public function outsourceRecruitment(): json;
	public function reportInterns(): json;
	public function genUniqueUrl(): json;*/

}

?>