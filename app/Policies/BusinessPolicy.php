<?php

namespace App\Policies;

use App\Models\User;

class BusinessPolicy
{
    public function manage(User $user): bool
    {
        return $user->isAdmin();
    }

    public function editContent(User $user): bool
    {
        return $user->active && in_array($user->role?->name, ['administrador', 'editor'], true);
    }
}
