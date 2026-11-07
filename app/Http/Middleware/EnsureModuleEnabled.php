<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Facades\Module;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureModuleEnabled
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        if (! Module::enabled($module)) {
            abort(404);
        }

        return $next($request);
    }
}
