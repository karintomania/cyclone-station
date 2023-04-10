<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Auth;

class IssueTokenService
{
    public function __invoke(string $email, string $password): array
    {
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $user = Auth::user();

            // remove existing tokens
            $user->tokens()->delete();

            $rawToken = $user->createToken('user_token');
            $token = explode('|', $rawToken->plainTextToken)[1];

            return [
                'success' => true,
                'token' => $token,
            ];
        } else {
            return [
                'success' => false,
            ];
        }
    }
}
