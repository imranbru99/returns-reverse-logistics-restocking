<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use App\Policies\Concerns\ChecksTenantIsolation;

final class ProductPolicy
{
    use ChecksTenantIsolation;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, 'product.view');
    }

    public function view(User $user, Product $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'product.view');
    }

    public function create(User $user): bool
    {
        return $this->allows($user, 'product.create');
    }

    public function update(User $user, Product $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'product.update');
    }

    public function delete(User $user, Product $record): bool
    {
        $this->assertSameTenant($user, $record);

        return $this->allows($user, 'product.delete');
    }

    private function allows(User $user, string $permission): bool
    {
        if ($user->is_platform_admin) {
            return true;
        }

        return $user->can($permission);
    }
}
