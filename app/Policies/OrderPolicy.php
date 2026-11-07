<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use App\Policies\Concerns\ChecksTenantIsolation;

final class OrderPolicy
{
    use ChecksTenantIsolation;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, 'order.view');
    }

    public function view(User $user, Order $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'order.view');
    }

    public function create(User $user): bool
    {
        return $this->allows($user, 'order.create');
    }

    public function update(User $user, Order $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'order.update');
    }

    public function delete(User $user, Order $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'order.delete');
    }

    private function allows(User $user, string $permission): bool
    {
        if ($user->is_platform_admin) {
            return true;
        }

        return $user->can($permission);
    }
}
