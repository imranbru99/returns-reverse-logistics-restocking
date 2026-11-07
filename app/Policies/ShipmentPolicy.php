<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Shipment;
use App\Models\User;
use App\Policies\Concerns\ChecksTenantIsolation;

final class ShipmentPolicy
{
    use ChecksTenantIsolation;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, 'shipment.view');
    }

    public function view(User $user, Shipment $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'shipment.view');
    }

    public function create(User $user): bool
    {
        return $this->allows($user, 'shipment.create');
    }

    public function update(User $user, Shipment $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'shipment.update');
    }

    public function delete(User $user, Shipment $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'shipment.delete');
    }

    private function allows(User $user, string $permission): bool
    {
        if ($user->is_platform_admin) {
            return true;
        }

        return $user->can($permission);
    }
}
