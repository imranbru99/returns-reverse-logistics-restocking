<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\ScansUploadedFiles;
use App\Models\Rma;
use App\Observers\RmaObserver;
use App\Services\Security\NullVirusScanner;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ScansUploadedFiles::class, NullVirusScanner::class);
    }

    public function boot(): void
    {
        Notification::extend('sandbox-mail', fn (): \App\Notifications\Channels\SandboxMailChannel => new \App\Notifications\Channels\SandboxMailChannel);

        if (config('returns.demo_mode')) {
            config(['mail.default' => 'log']);
        }
        if (! $this->app->isProduction()) {
            Model::preventLazyLoading();
        }

        Gate::guessPolicyNamesUsing(function (string $modelClass): string {
            return 'App\\Policies\\'.class_basename($modelClass).'Policy';
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute((int) env('API_RATE_LIMIT', 60))
                ->by($request->user()?->getAuthIdentifier() ?? $request->ip());
        });

        RateLimiter::for('returns', function (Request $request) {
            return Limit::perMinute((int) env('RETURNS_RATE_LIMIT', 120))
                ->by($request->user()?->getAuthIdentifier() ?? $request->ip());
        });

        RateLimiter::for('contact', function (Request $request) {
            return Limit::perMinute((int) env('CONTACT_RATE_LIMIT', 5))
                ->by($request->ip());
        });

        RateLimiter::for('portal-start', function (Request $request) {
            return Limit::perMinute((int) env('PORTAL_START_RATE_LIMIT', 20))
                ->by($request->ip());
        });

        Rma::observe(RmaObserver::class);
    }
}
