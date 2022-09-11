<?php

namespace App\Services\Traits\ModelAbstractions\Vendor;

use Illuminate\Http\Request;

use App\Services\Traits\ModelCRUD\CartCRUD;
use App\Services\Traits\ModelCRUD\BuyerCRUD;
use App\Services\Traits\ModelCRUD\BuyerBillingInfoCRUD;
use App\Services\Traits\ModelCRUD\BuyerShippingInfoCRUD;

trait VendorCartAbstraction
{
	//inherits all their methods:
	use CartCRUD;
	use BuyerCRUD;
	use BuyerBillingInfoCRUD;
	use BuyerShippingInfoCRUD;

	protected function VendorFetchEachBuyerDetailsService(Request $request)
	{
		$queryKeysValues = ['unique_buyer_id' => $request->unique_buyer_id];
		$each_buyer_detail = $this->BuyerReadSpecificService($queryKeysValues);

		//obtain associated cart_ids
		$assoc_cart_details = $this->CartReadAllLazySpecificService($queryKeysValues);
		$assoc_cart_ids = $assoc_cart_details->pluck('unique_cart_id');

		//obtain total purchase price made(plus shipping)
		$total_price_purchased = $assoc_cart_details->pluck('purchase_price')->sum();

		//obtain associated billing address
		$each_buyer_detail['buyer_billing_detail'] = $this?->BuyerBillingReadSpecificService($queryKeysValues);

		//obtain associated shipping address  
		$each_buyer_detail['buyer_shipping_detail'] = $this?->BuyerShippingReadSpecificService($queryKeysValues);

		//prepare final return:
		$each_buyer_detail['assoc_cart_ids'] = $assoc_cart_ids;//->toArray();
		$each_buyer_detail['total_purchase_made'] = $total_price_purchased;

		return $each_buyer_detail;
	}


	private function FetchPendingOrClearedCartDetails(Request $request)
	{
		$queryKeysValues = [
			'unique_cart_id' => $request->unique_cart_id,
			'payment_status' => $request->payment_status
		];

		$each_cart_detail = $this->CartReadSpecificService($queryKeysValues);
		return $each_cart_detail;
	}


	public function VendorFetchEachCartDetailsService(Request $request)
	{
		$cart_model = $this->FetchPendingOrClearedCartDetails($request);
		if(!$cart_model)
		{
			throw new \Exception("Cart Details not found! Ensure that this belongs to appropriate Cart Category.");
		}

		//get the buyer id:
		$buyer_id = $cart_model->unique_buyer_id;
		//now use this to get the buyer model:(this is in a bead to get buyer email and phone number)
		$queryKeysValues = ['unique_buyer_id' => $buyer_id];
		$buyer_model = $this->BuyerReadSpecificService($queryKeysValues);
		
		//begin to prepare the return array:
		$cart_model['cart_created_at'] = $cart_model->created_at;
		$cart_model['cart_updated_at'] = $cart_model->updated_at;
		$cart_model['buyer_email'] = $buyer_model->buyer_email;
		$cart_model['buyer_phone_number'] = $buyer_model->buyer_phone_number;

		return $cart_model;
	}	


	protected function VendorFetchAllCartBuyerIDsService() 
	{
		$all_cart_buyer_details = $this->BuyerReadAllLazyService();
		
		//the above returns a collection: loop through to get only the ids:
		$all_cart_buyer_ids = $all_cart_buyer_details->pluck('unique_buyer_id');
		return $all_cart_buyer_ids;
	}
	
	protected function VendorFetchAllCartIDsService(Request $request)
	{
	
		//assign:
		$queryKeysValues = ['payment_status' => $request->payment_status];

		$all_carts_found = $this->CartReadAllLazySpecificService($queryKeysValues);

		$all_unique_cart_ids = $all_carts_found->pluck('unique_cart_id');

		return $all_unique_cart_ids;
	}

	protected function  BuyerRemindPendingService($request)
	{
		//NOTE: This will be re-written in the standard mail format...
		
		//set SMTP server and port:
		ini_set('SMTP', env('GOOGLE_MAIL_SERVER'));
		ini_set('smtp_port', env('MAIL_PORT'));
		
		//NOTE: In a cart just created, the default value is pending:
        $mail_from = env('Vendor_EMAIL');
        $mail_header = "From:".  $mail_from;
        $mail_to = $request->buyer_email;
        $mail_subject = "Clear your pending Carts!";
        $mail_message = "You have created a pending Cart. Dont stop there - Clear your cart and keep enjoying our amazing offers. Best Regards!";
        
        $mail_was_sent = mail($mail_to, $mail_subject, $mail_message, $mail_header);
        if($mail_was_sent)
        {
            return true;
        }
	}
}