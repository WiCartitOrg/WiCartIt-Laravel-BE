<?php
namespace App\Services\Interfaces\Admin;

interface AdminInternsInterface {

	public function viewIntsAll(): json;
	public function viewAppliedInts(): json;
	public function viewIntsApplyDetails(): json;

	public function createIntsJobApply(): json;
	public function deleteIntsJobApply(): json;
	public function viewIntsUniqueUrl(): json;
	public function createIntsUniqueUrl(): json;
	public function deleteIntsUniqueUrl(): json;

}
	

?>