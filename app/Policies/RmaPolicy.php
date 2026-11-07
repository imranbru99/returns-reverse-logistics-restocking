<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Rma;
use App\Models\User;
use App\Policies\Concerns\ChecksTenantIsolation;

final class RmaPolicy
{
    use ChecksTenantIsolation;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, 'rma.view');
    }

    public function view(User $user, Rma $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'rma.view');
    }

    public function create(User $user): bool
    {
        return $this->allows($user, 'rma.create');
    }

    public function update(User $user, Rma $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'rma.update');
    }

    public function delete(User $user, Rma $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'rma.delete');
    }

    private function allows(User $user, string $permission): bool
    {
        if ($user->is_platform_admin) {
            return true;
        }

        return $user->can($permission);
    }
}
