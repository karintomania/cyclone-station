<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Auth\IssueTokenService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IssueTokenServiceTest extends TestCase
{
    use RefreshDatabase;

    private IssueTokenService $its;

    public function setUp(): void
    {
        parent::setUp();
        $this->its = $this->app->make(IssueTokenService::class);
    }

    public function test_service_returns_token_for_valid_credential(): void
    {
        $user = User::factory()->create();
        ['success' => $success, 'token' => $token] = $this->its->__invoke($user->email, 'password');

        $this->assertTrue($success);
        $this->assertMatchesRegularExpression("/\w+/", $token);
    }

    public function test_service_returns_false_for_invalid_credential(): void
    {
        $result = $this->its->__invoke('fakeEmail@example.com', 'wrong password');

        $this->assertFalse($result['success']);
        $this->assertFalse(isset($result['token']));
    }
}
