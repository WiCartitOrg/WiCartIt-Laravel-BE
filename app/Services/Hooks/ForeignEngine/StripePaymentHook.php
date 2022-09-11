<?php

namespace App\Services\Hooks\ForeignEngine;

use Illuminate\Http\Request;

trait StripePaymentHook
{
	//inherits all their methods:

	protected function  CallStripeService(array $userCardDetails): bool 
	{
		//init:
		//call our payment hooks that will interact with the API:
		
		$stripe_key = env('SECRET_PAYMENT_TEST_KEY');

		//$stripe_key = env('SECRET_PAYMENT_LIVE_KEY')//this will be after the account have been activated...

		$charge_success = false;

			$stripe = new \Stripe\StripeClient($stripe_key);
			$card_charge = $stripe->charges->create([

  				'amount' => $userCardDetails['charge_price'],

  				'currency' => strtolower($userCardDetails['cart_purchase_currency']),

  				'source' => [
  					'object' => 'card',
    				'number' => $userCardDetails['buyer_card_number'],
    				'exp_month' => $userCardDetails['buyer_card_exp_month'],
    				'exp_year' => $userCardDetails['buyer_card_exp_year'],
    				'cvc' => $userCardDetails['buyer_card_cvv'],
    			],

  				//'source' => 'tok_visa', // obtained with Stripe.js
  				/*'payment_method_details' => [
  					'type' => 'card',
  					'card' => [
  						'brand' => strtolower($userCardDetails['buyer_card_type']),
  						/*'number' => $userCardDetails['buyer_card_number'],
    					'exp_month' => $userCardDetails['buyer_card_exp_month'],
    					'exp_year' => $userCardDetails['buyer_card_exp_year'],
    					'cvc' => $userCardDetails['buyer_card_cvv'],
      				],
  				],*/

  				'receipt_email' => $userCardDetails['buyer_email'],

  				'description' => "Payment for Pending Cart:" . $userCardDetails['pending_cart_id']

			], [
  				'idempotency_key' => $userCardDetails['pending_cart_id']//use the pending cart id for ensuring that a single transaction request is not performed twice. 
			]);

			if($card_charge['status'] == 'succeeded')
			{
				$charge_success = true;
			}

			if(!$charge_success)
			{
				throw new \Exception('Error! Could not connect to Stripe!');
			}

		/*catch(\Stripe\Exception\CardException $e) 
		{
  			// Since it's a decline, \Stripe\Exception\CardException will be caught
  			echo 'Status is:' . $e->getHttpStatus() . '\n';
  			echo 'Type is:' . $e->getError()->type . '\n';
  			echo 'Code is:' . $e->getError()->code . '\n';
  			// param is '' in this case
  			echo 'Param is:' . $e->getError()->param . '\n';
  			echo 'Message is:' . $e->getError()->message . '\n';
		} 
		catch (\Stripe\Exception\RateLimitException $e) 
		{
  			// Too many requests made to the API too quickly
		} 
		catch (\Stripe\Exception\InvalidRequestException $e) 
		{
  			// Invalid parameters were supplied to Stripe's API
		} 
		catch (\Stripe\Exception\AuthenticationException $e) 
		{
  			// Authentication with Stripe's API failed
  			// (maybe you changed API keys recently)
		} 
		catch (\Stripe\Exception\ApiConnectionException $e) 
		{
 			 // Network communication with Stripe failed
		} 
		catch (\Stripe\Exception\ApiErrorException $e) 
		{
 		 	// Display a very generic error to the user, and maybe send
  			// yourself an email
		} 
		catch (Exception $e) 
		{
  			// Something else happened, completely unrelated to Stripe
		}*/

		return $charge_success;
	}

}