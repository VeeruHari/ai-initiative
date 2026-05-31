<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function getUsers(int $tenantId)
    {
        return User::where('tenant_id', $tenantId)->get();
    }

    public function createUser(array $data): User
    {
        return User::create($data);
    }

    public function updateUser(User $user, array $data): User
    {
        $user->update($data);

        return $user;
    }
}