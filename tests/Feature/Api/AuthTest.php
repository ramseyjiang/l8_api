<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Illuminate\Http\Response;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_api_request_works()
    {
        $response = $this->json('GET', route('api.entrance'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Api request works.');
    }

    public function test_login_success()
    {
        $user = User::factory()->create();

        $this->json('POST', route('api.auth.login'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertStatus(Response::HTTP_ACCEPTED)->assertJson(function (AssertableJson $json) use ($user) {
            $json->has('token')
                // ->where('user', $user)
                ->etc();
        });
    }

    public function test_login_failure()
    {
        $user = User::factory()->create();

        $this->json('POST', route('api.auth.login'), [
            'email' => $user->email,
            'password' => 'random',
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJson(function (AssertableJson $json) use ($user) {
            $json->has('message')
                ->where('message', 'The given data was invalid.')
                ->etc();
        });
    }

    public function test_log_out_success()
    {
        $this->loginAsUser();
        $this->json('POST', route('api.auth.logout'))
            ->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJson(['message' => 'Logged out']);
    }

    public function test_register_success()
    {
        $name = 'test register';
        $this->json('POST', route('api.auth.register'), [
            'name' => 'test register',
            'last_name' => 'test register',
            'email' => rand(1, 9999) . '@qq.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertStatus(Response::HTTP_CREATED)
            ->assertJson(['message' => 'Congrats ' . ucfirst($name) . '. Register success, please wait for approval.']);
    }
}
