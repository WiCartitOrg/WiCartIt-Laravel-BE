<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    //use RefreshDatabase;

    /*public function test_new_admins_can_logout()
    {
        $response = $this->patchJson(
            route('admin.logout'), 
            [
                'unique_admin_id' => 'YsBInb80f6',
            ],
            ['HTTP_Authorization' => 'Bearer ' . '2|ROPFqP5F9wwsnvbNtPYP9K9LEO74SItDyHSzUABA']
        );

        //$this->assertAuthenticated();
        //$response->assertNoContent();
        //$response->assertOk();
        $response->assertExactJson(['short_description' => "Verification Request Mail wasn't sent successfully!"]);
    }*/
}
