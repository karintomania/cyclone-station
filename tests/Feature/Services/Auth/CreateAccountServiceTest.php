<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Auth\CreateAccountService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateAccountServiceTest extends TestCase
{
    use RefreshDatabase;

    private CreateAccountService $cas;

    public function setUp(): void
    {
        parent::setUp();
        $this->cas = $this->app->make(CreateAccountService::class);
    }

    public function test_service_creates_a_user(): void
    {
        $input = [
            'name' => 'test name',
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        $result = $this->cas->__invoke($input['name'], $input['email'], $input['password']);

        $this->assertTrue($result['success']);

        $user = User::find($result['userId']);
        $this->assertEquals($input['name'], $user->name);
        $this->assertEquals($input['email'], $user->email);
        $this->assertTrue(Hash::check($input['password'], $user->password));
    }
}
