<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetTenantFromSubdomain
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (TenantContext::check()) {
            return $next($request);
        }

        $tenant = $this->resolveTenant($request);

        if ($tenant !== null) {
            TenantContext::set($tenant);
        }

        return $next($request);
    }

    private function resolveTenant(Request $request): ?Tenant
    {
        $host = strtolower($request->getHost());

        if ($host === '' || $this->isCentralHost($host)) {
            return null;
        }

        $tenant = Tenant::query()
            ->where('is_active', true)
            ->where(function ($query) use ($host): void {
                $query->where('domain', $host)
                    ->orWhere('subdomain', $host);
            })
            ->first();

        if ($tenant !== null) {
            return $tenant;
        }

        $centralHosts = $this->centralHosts();

        foreach ($centralHosts as $central) {
            $central = strtolower($central);
            $suffix = '.'.$central;

            if (! str_ends_with($host, $suffix)) {
                continue;
            }

            $subdomain = substr($host, 0, -strlen($suffix));

            if ($subdomain === '' || str_contains($subdomain, '.')) {
                continue;
            }

            return Tenant::query()
                ->where('is_active', true)
                ->where('subdomain', $subdomain)
                ->first();
        }

        return null;
    }

    private function isCentralHost(string $host): bool
    {
        return in_array($host, $this->centralHosts(), true);
    }

    /**
     * @return array<int, string>
     */
    private function centralHosts(): array
    {
        $configured = array_filter(array_map(
            'trim',
            explode(',', (string) config('modules.tenancy.central_domains', '')),
        ));

        if ($configured !== []) {
            return array_map('strtolower', $configured);
        }

        $appHost = parse_url((string) config('app.url'), PHP_URL_HOST);

        return $appHost ? [strtolower((string) $appHost)] : ['localhost'];
    }
}
