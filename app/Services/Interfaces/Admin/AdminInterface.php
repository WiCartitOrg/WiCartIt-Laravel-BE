<?php
namespace App\Services\Interfaces\Admin;

interface AdminInterface {

	public function loginDashboard(): json;

	public function viewIntsAll(): json;
	public function viewAppliedInts(): json;
	public function viewIntsApplyDetails(): json;

	public function createIntsJobApply(): json;
	public function deleteIntsJobApply(): json;
	public function viewIntsUniqueUrl(): json;
	public function createIntsUniqueUrl(): json;
	public function deleteIntsUniqueUrl(): json;
	
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

	public function viewIntEmpChats(): json;
	public function deleteIntEmpChats(): json;
	public function adminChatEmployer(): json;
	public function adminChatIntern(): json;
	public function deleteEmployer(): json;
	public function deleteIntern(): json;

	public function viewInternsPay(): json;
	public function viewEmployersPay(): json;

}

?>