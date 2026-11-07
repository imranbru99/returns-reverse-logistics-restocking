<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Refund;
use App\Models\User;
use App\Policies\Concerns\ChecksTenantIsolation;

final class RefundPolicy
{
    use ChecksTenantIsolation;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, 'refund.view');
    }

    public function view(User $user, Refund $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'refund.view');
    }

    public function create(User $user): bool
    {
        return $this->allows($user, 'refund.create');
    }

    public function update(User $user, Refund $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'refund.update');
    }

    public function delete(User $user, Refund $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'refund.delete');
    }

    private function allows(User $user, string $permission): bool
    {
        if ($user->is_platform_admin) {
            return true;
        }

        return $user->can($permission);
    }
}
