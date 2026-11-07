<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Disposition;
use App\Models\User;
use App\Policies\Concerns\ChecksTenantIsolation;

final class DispositionPolicy
{
    use ChecksTenantIsolation;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, 'disposition.view');
    }

    public function view(User $user, Disposition $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'disposition.view');
    }

    public function create(User $user): bool
    {
        return $this->allows($user, 'disposition.create');
    }

    public function update(User $user, Disposition $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'disposition.update');
    }

    public function delete(User $user, Disposition $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'disposition.delete');
    }

    private function allows(User $user, string $permission): bool
    {
        if ($user->is_platform_admin) {
            return true;
        }

        return $user->can($permission);
    }
}
