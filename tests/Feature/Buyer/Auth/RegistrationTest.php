<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*class RegistrationTest extends TestCase
{
    //use RefreshDatabase;

    /*public function test_new_buyers_can_register()
    {
        $response = $this->postJson(
            route('buyer.register'), 
            [
            'buyer_first_name' => 'Emmanuel',
            'buyer_middle_name' => 'Damilare',
            'buyer_last_name' => 'Adediji',
            'buyer_phone_number' => '+2349033068412',
            'buyer_email' => 'emmdammy@gmail.com',
            'buyer_password' => 'emma@12crown',
            'buyer_password_confirmation' => 'emma@12crown',
            ],
            []
        );

        //$this->assertAuthenticated();
        //$response->assertNoContent();
        //$response->assertOk();
        $response->assertExactJson(['short_description' => "Verification Request Mail wasn't sent successfully!"]);
    }
}*/
