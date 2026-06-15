<?php

namespace App\Services;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use Exception;

class UserService
{
    public function getUsers(int $tenantId, ?string $search = null, $status = null)
    {
        return User::query()
            ->when($search !== '', fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->where('tenant_id', $tenantId)
            ->when($status !== '', fn($q) => $q->where('is_active', $status))
            ->orderby('name', 'asc')
            ->paginate(20)
            ->withQueryString();
    }

    public function createUser(array $data): User
    {
        try {
            $user = DB::transaction(function () use ($data) {

                $password = $this->generateRandomPassword();

                $data['password'] = Hash::make($password);

                return User::create($data);
            });

            Password::sendResetLink([
                'email' => $user->email,
            ]);

            return $user;
        } catch (Exception $e) {
            throw new Exception(
                'Failed to create user: ' . $e->getMessage()
            );
        }
    }

    public function updateUser(User $user, array $data): User
    {
        try {
            $user->update($data);

            return $user;
        } catch (Exception $e) {
            throw new Exception(
                'Failed to update user: ' . $e->getMessage()
            );
        }
    }

    private function generateRandomPassword(): string
    {
        return Str::password(
            length: 12,
            letters: true,
            numbers: true,
            symbols: true
        );
    }
}