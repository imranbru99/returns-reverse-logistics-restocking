<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final class SetLocale
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $available = $this->availableLocales();
        $locale = $this->resolveLocale($request, $available);
        app()->setLocale($locale);

        if ($request->hasSession()) {
            $request->session()->put('locale', $locale);
        }

        return $next($request);
    }

    /**
     * @param  array<int, string>  $available
     */
    private function resolveLocale(Request $request, array $available): string
    {
        $fallback = (string) config('returns.locales.fallback', config('app.fallback_locale', 'en'));

        if ($request->hasSession()) {
            $sessionLocale = $request->session()->get('locale');

            if (is_string($sessionLocale) && in_array($sessionLocale, $available, true)) {
                return $sessionLocale;
            }
        }

        $user = Auth::user();

        if ($user !== null && isset($user->locale) && is_string($user->locale)) {
            if (in_array($user->locale, $available, true)) {
                return $user->locale;
            }
        }

        $tenant = TenantContext::get();

        if ($tenant !== null && in_array($tenant->locale, $available, true)) {
            return $tenant->locale;
        }

        $preferred = $request->getPreferredLanguage($available);

        if (is_string($preferred) && $preferred !== '') {
            return $preferred;
        }

        $default = (string) config('returns.locales.default', config('app.locale', 'en'));

        return in_array($default, $available, true) ? $default : $fallback;
    }

    /**
     * @return array<int, string>
     */
    private function availableLocales(): array
    {
        $locales = config('returns.locales.available', ['en', 'bn']);

        return array_values(array_intersect($locales, ['en', 'bn']));
    }
}
