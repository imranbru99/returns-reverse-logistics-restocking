<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ReceivingRecord;
use App\Models\User;
use App\Policies\Concerns\ChecksTenantIsolation;

final class ReceivingRecordPolicy
{
    use ChecksTenantIsolation;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, 'receiving_record.view');
    }

    public function view(User $user, ReceivingRecord $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'receiving_record.view');
    }

    public function create(User $user): bool
    {
        return $this->allows($user, 'receiving_record.create');
    }

    public function update(User $user, ReceivingRecord $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'receiving_record.update');
    }

    public function delete(User $user, ReceivingRecord $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'receiving_record.delete');
    }

    private function allows(User $user, string $permission): bool
    {
        if ($user->is_platform_admin) {
            return true;
        }

        return $user->can($permission);
    }
}
