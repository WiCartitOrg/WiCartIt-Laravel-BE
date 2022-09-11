<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthChangePasswordTest extends TestCase
{
    //use RefreshDatabase;

    /*public function test_auth_admins_can_change_password()
    {
        $response = $this->putJson(
            route('admin.change.password'), 
            [
                'unique_admin_id' => 'YsBInb80f6',
                'new_password' => 'Emmy@234ade',
                'new_password_confirmation' => 'Emmy@234ade'
            ],
            ['HTTP_Authorization' => 'Bearer ' . '2|ROPFqP5F9wwsnvbNtPYP9K9LEO74SItDyHSzUABA']
        );

        //$this->assertAuthenticated();
        //$response->assertNoContent();
        //$response->assertOk();
        $response->assertExactJson(['short_description' => "Verification Request Mail wasn't sent successfully!"]);
    }*/
}
