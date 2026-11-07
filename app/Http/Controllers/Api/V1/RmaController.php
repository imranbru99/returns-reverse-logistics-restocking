<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Rma;
use App\Services\Rma\CreateRmaAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class RmaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->integer('per_page', 15), 1), 100);

        $rmas = Rma::query()
            ->with(['customer:id,name,email', 'order:id,number'])
            ->latest()
            ->paginate($perPage);

        return response()->json($rmas);
    }

    public function show(Rma $rma): JsonResponse
    {
        $rma->load(['items.product', 'customer', 'order', 'statusHistories']);

        return response()->json(['data' => $rma]);
    }

    public function store(Request $request, CreateRmaAction $createRma): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => ['required', 'string'],
            'reason' => ['nullable', 'string', 'max:500'],
            'customer_notes' => ['nullable', 'string', 'max:2000'],
            'lines' => ['required', 'array', 'min:1'],
            'lines.*.order_item_id' => ['required', 'string'],
            'lines.*.quantity' => ['required', 'integer', 'min:1'],
            'lines.*.reason' => ['nullable', 'string', 'max:255'],
        ]);

        $order = Order::query()->findOrFail($validated['order_id']);

        $rma = $createRma->execute(
            $order,
            $validated['lines'],
            $validated['reason'] ?? null,
            $validated['customer_notes'] ?? null,
        );

        return response()->json(['data' => $rma->load('items')], 201);
    }

    public function update(Request $request, Rma $rma): JsonResponse
    {
        $validated = $request->validate([
            'internal_notes' => ['nullable', 'string', 'max:5000'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $rma->update($validated);

        return response()->json(['data' => $rma->fresh()]);
    }

    public function destroy(Rma $rma): JsonResponse
    {
        $rma->delete();

        return response()->json(null, 204);
    }
}
