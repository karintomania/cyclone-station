<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Auth\IssueTokenService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IssueTokenControllerTest extends TestCase
{
    use RefreshDatabase;

    // private IssueTokenService $its;

    // public function setUp(): void{
    //     parent::setUp();
    //     $this->its = $this->app->make(IssueTokenService::class);
    // }

    public function test_token_returns_token_for_valid_credential(): void
    {
        $user = User::factory()->create();
        $response = $this->withHeaders([
            'user' => $user->email,
            'password' => 'password',
        ])->get('/auth/token');

        $response->assertOk();
        $token = $response->getContent();
        $this->assertMatchesRegularExpression("/\w+/", $token);
    }

    public function test_token_returns_401_for_invalid_credential(): void
    {
        $response = $this->withHeaders([
            'user' => 'wrongEmail@example.com',
            'password' => 'wrong_password',
        ])->get('/auth/token');

        $response->assertStatus(401);
        $responseContent = $response->getContent();
        $this->assertEquals('Invalid credential', $responseContent);
    }
}
