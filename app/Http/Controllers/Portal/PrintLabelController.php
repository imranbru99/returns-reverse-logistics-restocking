<?php

declare(strict_types=1);

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Rma;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class PrintLabelController extends Controller
{
    public function __invoke(Request $request, string $rma): View
    {
        if (! $request->hasValidSignature()) {
            abort(403);
        }

        $rma = Rma::query()->withoutGlobalScopes()->findOrFail($rma);
        $rma->load(['customer', 'order']);

        return view('portal.label', compact('rma'));
    }
}
