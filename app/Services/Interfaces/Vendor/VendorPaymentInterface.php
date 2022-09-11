<?php
namespace App\Services\Interfaces\Vendor;

interface PaymentInterface {
	
	public function internPay():json;
	public function employerPay():json;

}

?>