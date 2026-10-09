<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * بررسی کند آیا یک کاربر می‌تواند کاربر دیگری را ببیند
     */
    public function view($authId, User $targetUser, string $role): bool
    {
        // Admin همه رو می‌بینه
        if ($role === 'admin') {
            return true;
        }

        // Reseller فقط مشتریان خودش
        if ($role === 'reseller') {
            return $targetUser->reseller_id === $authId;
        }

        // Customer فقط خودش
        if ($role === 'customer') {
            return $authId === $targetUser->id;
        }

        return false;
    }

    /**
     * بررسی کند آیا می‌تواند تغییر بدهد
     */
    public function update($authId, User $targetUser, string $role): bool
    {
        // Admin همه رو می‌تونه تغییر بده
        if ($role === 'admin') {
            return true;
        }

        // Reseller می‌تونه مشتریان خودش رو تغییر بده
        if ($role === 'reseller') {
            return $targetUser->reseller_id === $authId;
        }

        // Customer فقط خودش
        if ($role === 'customer') {
            return $authId === $targetUser->id;
        }

        return false;
    }

    /**
     * بررسی کند آیا می‌تواند حذف کند
     */
    public function delete($authId, User $targetUser, string $role): bool
    {
        // فقط Admin می‌تونه حذف کنه
        if ($role === 'admin') {
            return true;
        }

        return false;
    }
}
