<?php

declare(strict_types=1);

namespace App\Policies\Concerns;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;

trait ChecksTenantIsolation
{
    protected function assertSameTenant(User $user, Model $model): void
    {
        $tenantId = $model->getAttribute('tenant_id');

        if ($tenantId === null) {
            return;
        }

        if ($user->is_platform_admin) {
            return;
        }

        if (! $user->belongsToTenant((string) $tenantId)) {
            throw new AuthorizationException('This resource does not belong to your organization.');
        }
    }
}
