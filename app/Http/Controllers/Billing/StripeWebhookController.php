<?php

declare(strict_types=1);

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

final class StripeWebhookController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $signature = $request->header('Stripe-Signature', '');
        $secret = config('services.stripe.webhook_secret');

        if (config('returns.demo_mode')) {
            Log::info('stripe.webhook.sandbox', [
                'type' => $request->input('type'),
                'signature_present' => $signature !== '',
            ]);

            return response()->json(['received' => true, 'mode' => 'sandbox']);
        }

        if (! is_string($secret) || $secret === '') {
            Log::warning('stripe.webhook.missing_secret');

            return response()->json(['error' => 'Webhook not configured'], 503);
        }

        Log::info('stripe.webhook.received', [
            'type' => $request->input('type'),
            'has_signature' => $signature !== '',
        ]);

        return response()->json(['received' => true]);
    }
}
