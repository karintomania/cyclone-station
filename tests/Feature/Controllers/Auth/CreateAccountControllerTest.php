<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateAccountControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_token_returns_token_for_valid_credential(): void
    {
        $body = [
            'name' => 'test name',
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/auth/createAccount', $body);

        $response->assertOk();
        $userId = $response->getContent();

        $user = User::find($userId);
        $this->assertEquals($body['name'], $user->name);
        $this->assertEquals($body['email'], $user->email);
        $this->assertTrue(Hash::check($body['password'], $user->password));
    }
}
