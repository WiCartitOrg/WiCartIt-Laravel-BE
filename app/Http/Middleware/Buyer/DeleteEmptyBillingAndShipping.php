<?php

namespace App\Http\Middleware\Buyer;

use Illuminate\Http\Request;

use Closure;
use App\Services\Traits\ModelAbstractions\Buyer\BuyerBillingAndShippingAbstraction;

final class DeleteEmptyBillingAndShipping
{
	use BuyerBillingAndShippingAbstraction;

	public function handle(Request $request, Closure $next)
	{
		//Before:
		//delete all collections where :
        $billingDeleteKeysValues = [
            'billing_username' => 'null',
            'billing_user_company' => 'null', 
            'billing_country' => 'null', 
            'billing_state'  => 'null',
            'billing_city_or_town'  => 'null',
            'billing_street_or_close'  => 'null',
            'billing_home_apartment_suite_or_unit'  => 'null',
            'billing_phone_number'  => 'null',
            'billing_email'  => 'null',
        ];

        $shippingDeleteKeysValues = [
            'shipping_username' => 'null',
            'shipping_user_company' => 'null', 
            'shipping_country' => 'null', 
            'shipping_state'  => 'null',
            'shipping_city_or_town'  => 'null',
            'shipping_street_or_close'  => 'null',
            'shipping_home_apartment_suite_or_unit'  => 'null',
            'shipping_phone_number'  => 'null',
            'shipping_email'  => 'null',
        ];

		$this?->BuyerBillingAndShippingDeleteAllNullService($billingDeleteKeysValues, $shippingDeleteKeysValues);

		//After:
		//Pass to next stack:
		$response = $next($request);
        return $response;
	}
	
}