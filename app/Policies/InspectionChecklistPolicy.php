<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\InspectionChecklist;
use App\Models\User;
use App\Policies\Concerns\ChecksTenantIsolation;

final class InspectionChecklistPolicy
{
    use ChecksTenantIsolation;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, 'inspection_checklist.view');
    }

    public function view(User $user, InspectionChecklist $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'inspection_checklist.view');
    }

    public function create(User $user): bool
    {
        return $this->allows($user, 'inspection_checklist.create');
    }

    public function update(User $user, InspectionChecklist $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'inspection_checklist.update');
    }

    public function delete(User $user, InspectionChecklist $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'inspection_checklist.delete');
    }

    private function allows(User $user, string $permission): bool
    {
        if ($user->is_platform_admin) {
            return true;
        }

        return $user->can($permission);
    }
}
