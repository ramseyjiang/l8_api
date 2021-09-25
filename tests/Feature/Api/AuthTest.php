<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Illuminate\Http\Response;

class AuthTest extends TestCase
{
    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling(); //See clearly wrong messages when unittest fails.
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_api_request()
    {
        $response = $this->get('/api');

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_login_success()
    {
        $user = $this->loginAsUser();

        $this->json('POST', route('api.auth.login'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'user' => ['name', 'email'],
                'token'
            ]);
    }

    public function test_login_failure()
    {
        $user = $this->loginAsUser();

        $this->json('POST', route('api.auth.login'), [
            'email' => $user->email,
            'password' => 'random',
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['message'])
            ->assertJson(['message' => 'These credentials do not match our records!']);
    }

    public function test_log_out_success()
    {
        $this->loginAsUser();
        $this->json('POST', route('api.auth.logout'))
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(['message' => 'Logged out']);
    }

    public function test_not_login_access_any_routes_need_login_failure()
    {
        $this->withExceptionHandling();
        $this->withHeaders(['Accept' => 'application/json',])
            ->json('POST', route('api.auth.logout')) //this route can be replaced to any route needs login,
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJsonStructure(['message'])
            ->assertJson([
                'message' => 'Unauthenticated.'
            ]);
    }
}
