<?php

namespace App\Services\Traits\ModelAbstractions\Vendor;

use Illuminate\Http\Request;
//use Illuminate\Database\Eloquent\Collection;

use App\Services\Traits\ModelCRUD\ProductCRUD;
use App\Services\Traits\ModelCRUD\PaymentInfoCRUD;
use App\Services\Traits\ModelCRUD\CartCRUD;
use App\Services\Traits\ModelCRUD\VendorGenBizCRUD;

use App\Services\Traits\Utilities\ComputeUniqueIDService;

trait VendorOverviewAbstraction 
{
	use ProductCRUD;
	use PaymentInfoCRUD;
	use CartCRUD;
	

	protected function VendorGetSalesDataService()
	{
		//get all payment where is_success == true;
		$queryKeysValues = ['is_success' => true];
		$allSuccessfulPayDetails = $this->PaymentReadAllLazySpecificService($queryKeysValues);
		//init:
		$salesData = array();

		$total_sales_made = $allSuccessfulPayDetails->pay_amount->sum();
		//add to sales data:
		$salesData['totalsales'] = $total_sales_made;

		$month = ['January', 'February', 'March', 'April', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

		foreach($month as $each_month)
		{
			$monthQueryKeysValues = ['paymentMonth' => $each_month];
			$salesMadePerMonth = $allSuccessfulPayDetails->where($monthQueryKeysValues)->pay_amount->sum();

			//add this to the sales Data:
			$salesData['$each_month'] = $salesMadePerMonth;//e.g.['January'=> 50,000]
		}

		return $salesData;
	}

	//frequently bought products:
	protected function VendorViewFrequentService()
	{
		$queryKeysValues = ['is_success' => true];
		$allSuccessfulPayDetails = $this->PaymentReadAllLazySpecificService($queryKeysValues);

		$all_cleared_cart_ids = $allSuccessfulPayDetails->cart_id;

		$cartQueryKeysValues = ['cart_id' => $all_cleared_cart_ids];
		//now use this cleared id to locate bought goods
		$all_cart_details = $this->CartReadAllLazySpecificService($cartQueryKeysValues);
		$product_ids = $all_cart_details->Product_id;

		//find the highest occuring ids:
		//by adding each processed ids to an array:
		$id_array = array();
		$init = -1;

		$id_array[$init++] = $product_ids;
		//Finding the highest number of occurrence in arrays in PHP:

		// new array containing frequency of values of $arr 
    	$arr_freq = array_count_values($id_array);     
      
     	// arranging the new $arr_freq in decreasing order  
     	// of occurrences 
     	arsort($arr_freq); 
       
     	// $new_arr containing the keys of sorted array 
     	$new_arr = array_keys($arr_freq);

     	//get first 10 Product_ids that are most frequent:
     	$frequentBoughtProductDetails = array();
     	for($i=0; $i<=20; $i++)
     	{
     		$queryKeysValues = ['Product_id' => $new_arr[$i]];
     		$frequentBoughtProductDetails = $this->ProductReadSpecificAllLazyService($queryKeysValues);
     	}

     	return $frequentBoughtProductDetails;
	}

}

?>