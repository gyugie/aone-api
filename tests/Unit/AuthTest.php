<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_user_register()
    {
        $user = User::factory()->make();
        $response = $this->postJson(route('user.register'), array_merge($user->toArray(), ['password' => 'password']));
        $this->assertTrue($response->status() === 200);
        $this->assertArrayHasKey('token', $response->getOriginalContent());
    }

    public function test_auth_login()
    {
        $user = User::factory()->create([ 'password' => 'password']);
        $response = $this->postJson(route('user.login'), ['email' => $user->email, 'password' => 'password']);

        $this->assertTrue($response->status() === 200);
        $this->assertArrayHasKey('token', $response->getOriginalContent());
    }

    public function test_users()
    {
        $user = User::factory()->create([ 'password' => 'password']);

        \Laravel\Sanctum\Sanctum::actingAs($user, ['*']);

        $response = $this->getJson(route('users.index'));
        dd($response->getOriginalContent());

    }
}
