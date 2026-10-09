<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function view($authId, User $targetUser, string $role): bool
    {
        if ($role === 'admin') {
            return true;
        }

        if ($role === 'reseller') {
            return $targetUser->reseller_id === $authId;
        }

        if ($role === 'customer') {
            return $authId === $targetUser->id;
        }

        return false;
    }

    public function update($authId, User $targetUser, string $role): bool
    {
        if ($role === 'admin') {
            return true;
        }

        if ($role === 'reseller') {
            return $targetUser->reseller_id === $authId;
        }

        if ($role === 'customer') {
            return $authId === $targetUser->id;
        }

        return false;
    }

    public function delete($authId, User $targetUser, string $role): bool
    {
        if ($role === 'admin') {
            return true;
        }

        return false;
    }
}
