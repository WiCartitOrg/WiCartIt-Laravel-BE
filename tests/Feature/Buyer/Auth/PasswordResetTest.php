<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/*class PasswordResetTest extends TestCase
{
    //use RefreshDatabase;

    /*public function test_reset_password_link_can_be_requested()
    {*/
        /*Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class);*/

        /*$response = $this->postJson(
            route('buyer.password.reset.link'), 
            [
                'buyer_email' => 'emmdammy@gmail.com',
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
            route('buyer.reset.password'), 
            [
                'unique_buyer_id' => 'Jt0AUFGSky',
                'new_password' => 'Y_emmy@12e_crown',
                'new_password_confirmation' => 'Y_emmy@12e_crown',
            ],
            [
                'HTTP_Authorization' => 'Bearer ' . '20|smy72I9llvyAr8ngR5fze7f1HjIOQt1PeWZmKwed',
                'Accept' => 'application/json'
            ]
        );

        //$this->assertAuthenticated();
        //$response->assertNoContent();
        //$response->assertOk();
        $response->assertExactJson(['short_description' => "Verification Request Mail wasn't sent successfully!"]);
    }

}*/
