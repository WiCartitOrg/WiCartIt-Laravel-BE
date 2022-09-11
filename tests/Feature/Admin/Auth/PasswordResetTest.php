<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    //use RefreshDatabase;

    /*public function test_reset_password_link_can_be_requested()
    {
        /*Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class);*/

        /*$response = $this->postJson(
            route('admin.password.reset.link'), 
            [
                'admin_email' => 'emmanueladediji@gmail.com',
            ],
            []
        );

        //$this->assertAuthenticated();
        //$response->assertNoContent();
        //$response->assertOk();
        $response->assertExactJson(['short_description' => "Verification Request Mail wasn't sent successfully!"]);

    }*/

    /*public function test_password_can_be_reset_with_valid_token()
    {
        /*Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $response = $this->post('/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response->assertSessionHasNoErrors();

            return true;
        });*/

        /*$response = $this->put(
            route('admin.reset.password'), 
            [
                'unique_admin_id' => 'YsBInb80f6',
                'new_password' => 'Y_emmy@12e_crown',
                'new_password_confirmation' => 'Y_emmy@12e_crown',
            ],
            [
                'HTTP_Authorization' => 'Bearer ' . '3|wT9MaJfSy0CFSQi4VYNt1NFLPjHXjGvfihGue8r1',
                'Accept' => 'application/json'
            ]
        );

        //$this->assertAuthenticated();
        //$response->assertNoContent();
        //$response->assertOk();
        $response->assertExactJson(['short_description' => "Verification Request Mail wasn't sent successfully!"]);
    }*/

}
