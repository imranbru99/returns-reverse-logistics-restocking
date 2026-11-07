<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\HealthController;
use App\Http\Controllers\Api\V1\RmaController;
use App\Http\Controllers\Billing\StripeWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/webhooks/stripe', StripeWebhookController::class)->name('webhooks.stripe');

Route::prefix('v1')->group(function (): void {
    Route::get('/health', HealthController::class);

    Route::middleware(['auth:sanctum', 'api.tenant', 'module:rma-management'])->group(function (): void {
        Route::get('/rmas', [RmaController::class, 'index']);
        Route::get('/rmas/{rma}', [RmaController::class, 'show']);
        Route::post('/rmas', [RmaController::class, 'store']);
        Route::patch('/rmas/{rma}', [RmaController::class, 'update']);
        Route::delete('/rmas/{rma}', [RmaController::class, 'destroy']);
    });
});
