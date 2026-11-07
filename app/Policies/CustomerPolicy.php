<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;
use App\Policies\Concerns\ChecksTenantIsolation;

final class CustomerPolicy
{
    use ChecksTenantIsolation;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, 'customer.view');
    }

    public function view(User $user, Customer $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'customer.view');
    }

    public function create(User $user): bool
    {
        return $this->allows($user, 'customer.create');
    }

    public function update(User $user, Customer $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'customer.update');
    }

    public function delete(User $user, Customer $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'customer.delete');
    }

    private function allows(User $user, string $permission): bool
    {
        if ($user->is_platform_admin) {
            return true;
        }

        return $user->can($permission);
    }
}
