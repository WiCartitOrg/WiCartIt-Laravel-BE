<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    //use RefreshDatabase;

    /*public function test_boss_admin_can_register_hired_admin()
    {
        $response = $this->postJson(
            route('admin.register'), 
            [
            'admin_first_name' => 'Techrite',
            'admin_middle_name' => 'Solutions',
            'admin_last_name' => 'Ltd',
            'admin_phone_number' => '+2349033068412',
            'admin_email' => 'emmdammy@gmail.com',
            'admin_password' => 'emma@12crown',
            'admin_password_confirmation' => 'emma@12crown',
            ],
            []
        );

        //$this->assertAuthenticated();
        //$response->assertNoContent();
        //$response->assertOk();
        $response->assertExactJson(['short_description' => "Verification Request Mail wasn't sent successfully!"]);
    }*/

    /*public function test_boss_admin_can_delete_hired_admin()
    {
        $response = $this->postJson(
            route('admin.register'), 
            [
            'admin_first_name' => 'Techrite',
            'admin_middle_name' => 'Solutions',
            'admin_last_name' => 'Ltd',
            'admin_phone_number' => '+2349033068412',
            'admin_email' => 'emmdammy@gmail.com',
            'admin_password' => 'emma@12crown',
            'admin_password_confirmation' => 'emma@12crown',
            ],
            []
        );

        //$this->assertAuthenticated();
        //$response->assertNoContent();
        //$response->assertOk();
        $response->assertExactJson(['short_description' => "Verification Request Mail wasn't sent successfully!"]);
    }*/
}
