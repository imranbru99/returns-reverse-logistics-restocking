<?php

declare(strict_types=1);

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class LocaleController extends Controller
{
    public function __invoke(Request $request, string $locale): RedirectResponse
    {
        $available = array_intersect(
            config('returns.locales.available', ['en', 'bn']),
            ['en', 'bn'],
        );

        if (! in_array($locale, $available, true)) {
            abort(404);
        }

        $request->session()->put('locale', $locale);

        return redirect()->back(fallback: url('/'));
    }
}
