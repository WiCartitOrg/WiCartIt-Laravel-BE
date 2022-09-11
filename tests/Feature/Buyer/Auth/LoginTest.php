<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*class LoginTest extends TestCase
{
    //use RefreshDatabase;

    /*public function test_new_buyers_can_login()
    {
        $response = $this->patchJson(
            route('buyer.login'), 
            [
                'buyer_email' => 'emmdammy@gmail.com',
                'buyer_password' => 'emma@12crown',
            ],
            []
        );

        //$this->assertAuthenticated();
        //$response->assertNoContent();
        //$response->assertOk();
        $response->assertExactJson(['short_description' => "Verification Request Mail wasn't sent successfully!"]);
    }
}*/
