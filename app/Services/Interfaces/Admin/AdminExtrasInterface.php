<?php
namespace App\Services\Interfaces\Admin;

interface AdminExtrasInterface {

	public function viewIntEmpChats(): json;
	public function deleteIntEmpChats(): json;
	public function viewReportedInterns(): json;
	public function viewSkillsTest(): json;
	public function setSkillsTest(): json;
	public function adminChatEmployer(): json;
	public function adminChatIntern(): json;
	public function deleteEmployer(): json;
	public function deleteIntern(): json;
	public function viewInternsPay(): json;
	public function viewEmployersPay(): json;

}

?>