<?php
namespace App\Services\Interfaces\Admin;

interface AdminEmployersInterface {
	
	public function viewEmpAll(): json;
	public function viewEmpJobPosts(): json;
	public function createEmpJobPosts(): json;
	public function deleteEmpJobPosts(): json;
	public function viewEmpUniqueUrls(): json;
	public function createEmpUniqueUrl(): json;
	public function deleteEmpUniqueUrl(): json;

	public function viewReportedInterns(): json;
	public function viewSkillsTest(): json;
	public function setSkillsTest(): json;

}

?>