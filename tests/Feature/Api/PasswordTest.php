<?php

namespace Tests\Feature\Api;

use Illuminate\Http\Response;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;

class PasswordTest extends TestCase
{
    public function test_reset_password_link_send_success()
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->json('POST', route('api.password.forgot'), [
            'email' => $user->email,
        ])->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJson(['message' => 'We have emailed your password reset link!']);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_reset_password_link_send_failure()
    {
        $this->json('POST', route('api.password.forgot'), [
            'email' => random_int(10000, 99999) . '@qq.com',
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(['message' => 'The given data was invalid.']);
    }

    public function test_reset_password_success()
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->json('POST', route('api.password.forgot'), [
            'email' => $user->email,
        ])->assertStatus(Response::HTTP_ACCEPTED);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $this->json('POST', route('api.password.reset'), [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ])->assertStatus(Response::HTTP_ACCEPTED)
                ->assertJson(['message' => 'Congrats, your password reset is successful.']);

            return true;    //If not add this, it always has an error "Failed asserting that false is true."
        });
    }

    public function test_reset_password_failure()
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->json('POST', route('api.password.forgot'), [
            'email' => $user->email,
        ])->assertStatus(Response::HTTP_ACCEPTED);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $this->json('POST', route('api.password.reset'), [
                'token' => random_int(10000, 99999),
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ])->assertStatus(Response::HTTP_BAD_REQUEST)
                ->assertJson(['message' => 'This password reset token is invalid.']);

            return true;    //If not add this, it always has an error "Failed asserting that false is true."
        });
    }
}
