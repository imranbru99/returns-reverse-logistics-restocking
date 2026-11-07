<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;

final class HealthController
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'service' => (string) config('returns.brand.name', 'Returns Platform'),
            'version' => 'v1',
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
