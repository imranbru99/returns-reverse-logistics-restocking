<?php

declare(strict_types=1);

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

final class ContactController extends Controller
{
    public function show(): View
    {
        return view('marketing.contact');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'company' => ['nullable', 'string', 'max:120'],
            'subject' => ['required', 'string', 'max:160'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $submission = ContactSubmission::query()->create($validated);

        if (config('returns.demo_mode')) {
            Log::info('demo.contact.submission', $submission->only(['name', 'email', 'subject']));
        } else {
            Notification::route('mail', config('mail.from.address'))
                ->notify(new \App\Notifications\ContactSubmissionReceived($submission));
        }

        return redirect()
            ->route('marketing.contact')
            ->with('status', 'Thanks — we received your message.');
    }
}
