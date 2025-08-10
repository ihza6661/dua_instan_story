<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function createAdminUser(array $data): User
    {
        return User::create([
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => 'admin',
        ]);
    }

    public function updateAdminUser(User $user, array $data): User
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        return $user;
    }

    public function deleteUser(User $user): void
    {
        $user->delete();
    }
}
