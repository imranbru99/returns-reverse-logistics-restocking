<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ResolveApiTenant
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenantId = $request->header('X-Tenant-ID');

        if (! is_string($tenantId) || $tenantId === '') {
            return response()->json(['message' => 'X-Tenant-ID header is required.'], 422);
        }

        $tenant = Tenant::query()->whereKey($tenantId)->first()
            ?? Tenant::query()->where('slug', $tenantId)->first();

        if ($tenant === null) {
            return response()->json(['message' => 'Tenant not found.'], 404);
        }

        if (! $tenant->is_active) {
            return response()->json(['message' => 'Tenant is inactive.'], 403);
        }

        TenantContext::set($tenant);
        setPermissionsTeamId($tenant->getKey());

        return $next($request);
    }
}
