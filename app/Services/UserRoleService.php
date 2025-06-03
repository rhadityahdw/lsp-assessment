<?php

namespace App\Services;

use App\Models\User;
use Spatie\Permission\Models\Role;

class UserRoleService
{
    /**
     * Set a single role for user, replacing any existing roles.
     */
    public function setRole(User $user, string $role): void
    {
        if (!Role::where('name', $role)->exists()) {
            throw new \InvalidArgumentException("Role '{$role}' tidak ditemukan.");
        }

        $user->syncRoles([$role]);
    }

    /**
     * Get user's current role (assuming single role system).
     */
    public function getRole(User $user): ?string
    {
        return $user->getRoleNames()->first();
    }

    /**
     * Check if user has a specific role.
     */
    public function is(User $user, string $role): bool
    {
        return $user->hasRole($role);
    }
}