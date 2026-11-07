<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\VendorClaim;
use App\Models\User;
use App\Policies\Concerns\ChecksTenantIsolation;

final class VendorClaimPolicy
{
    use ChecksTenantIsolation;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, 'vendor_claim.view');
    }

    public function view(User $user, VendorClaim $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'vendor_claim.view');
    }

    public function create(User $user): bool
    {
        return $this->allows($user, 'vendor_claim.create');
    }

    public function update(User $user, VendorClaim $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'vendor_claim.update');
    }

    public function delete(User $user, VendorClaim $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'vendor_claim.delete');
    }

    private function allows(User $user, string $permission): bool
    {
        if ($user->is_platform_admin) {
            return true;
        }

        return $user->can($permission);
    }
}
