<?php

namespace Tests\Feature\Api;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Illuminate\Http\Response;

class UserTest extends TestCase
{
    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->loginAsUser();
        $this->withoutExceptionHandling(); //See clearly wrong message when unittest fails.
    }

    public function test_get_current_user()
    {
        $this->json('GET', route('api.user.getCurrentUser'))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                ['name', 'email']
            );
    }

    public function test_get_all_users()
    {
        $this->json('GET', route('api.user.getAllUsers'))
            ->assertStatus(Response::HTTP_OK);
    }

    public function test_show_user_found()
    {
        $this->json('GET', route('api.user.show', $this->user->id))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                ['name', 'email']
            );
    }

    public function test_show_user_not_found()
    {
        $userId = 99999;
        $this->json('GET', route('api.user.show', $userId))
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure(['message'])
            ->assertJson(
                fn (AssertableJson $json)
                => $json->has(1)
                    ->where('message', 'User does not exist.')
            );
    }
}
