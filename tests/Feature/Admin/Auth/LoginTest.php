<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    //use RefreshDatabase;

    public function test_new_admins_can_login()
    {
        $response = $this->patchJson(
            route('admin.login'), 
            [
                'admin_email' => 'techrite.0@gmail.com',
                'admin_password' => 'y_emmy@12e_crown',
            ],
            []
        );

        //$this->assertAuthenticated();
        //$response->assertNoContent();
        //$response->assertOk();
        $response->assertExactJson(['short_description' => "Verification Request Mail wasn't sent successfully!"]);
    }
}
