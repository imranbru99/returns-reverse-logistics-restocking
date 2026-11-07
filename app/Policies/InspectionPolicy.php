<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Inspection;
use App\Models\User;
use App\Policies\Concerns\ChecksTenantIsolation;

final class InspectionPolicy
{
    use ChecksTenantIsolation;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, 'inspection.view');
    }

    public function view(User $user, Inspection $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'inspection.view');
    }

    public function create(User $user): bool
    {
        return $this->allows($user, 'inspection.create');
    }

    public function update(User $user, Inspection $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'inspection.update');
    }

    public function delete(User $user, Inspection $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'inspection.delete');
    }

    private function allows(User $user, string $permission): bool
    {
        if ($user->is_platform_admin) {
            return true;
        }

        return $user->can($permission);
    }
}
