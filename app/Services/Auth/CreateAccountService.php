<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAccountService
{
    public function __invoke(string $name, string $email, string $password): array
    {
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);

        $success = $user->save();
        if ($success) {
            return [
                'success' => true,
                'userId' => $user->id,
            ];
        } else {
            return ['success' => false];
        }
    }
}
