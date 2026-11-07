<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\CarrierAccount;
use App\Models\User;
use App\Policies\Concerns\ChecksTenantIsolation;

final class CarrierAccountPolicy
{
    use ChecksTenantIsolation;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, 'carrier_account.view');
    }

    public function view(User $user, CarrierAccount $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'carrier_account.view');
    }

    public function create(User $user): bool
    {
        return $this->allows($user, 'carrier_account.create');
    }

    public function update(User $user, CarrierAccount $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'carrier_account.update');
    }

    public function delete(User $user, CarrierAccount $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'carrier_account.delete');
    }

    private function allows(User $user, string $permission): bool
    {
        if ($user->is_platform_admin) {
            return true;
        }

        return $user->can($permission);
    }
}
