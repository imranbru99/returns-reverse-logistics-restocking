<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\RefurbishmentWorkOrder;
use App\Models\User;
use App\Policies\Concerns\ChecksTenantIsolation;

final class RefurbishmentWorkOrderPolicy
{
    use ChecksTenantIsolation;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, 'refurbishment_work_order.view');
    }

    public function view(User $user, RefurbishmentWorkOrder $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'refurbishment_work_order.view');
    }

    public function create(User $user): bool
    {
        return $this->allows($user, 'refurbishment_work_order.create');
    }

    public function update(User $user, RefurbishmentWorkOrder $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'refurbishment_work_order.update');
    }

    public function delete(User $user, RefurbishmentWorkOrder $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'refurbishment_work_order.delete');
    }

    private function allows(User $user, string $permission): bool
    {
        if ($user->is_platform_admin) {
            return true;
        }

        return $user->can($permission);
    }
}
