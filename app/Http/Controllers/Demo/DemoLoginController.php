<?php

declare(strict_types=1);

namespace App\Http\Controllers\Demo;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class DemoLoginController extends Controller
{
    public function index(): View
    {
        $tenants = Tenant::query()
            ->whereNotNull('demo_key')
            ->orderBy('name')
            ->get();

        $roles = [
            'owner' => 'Tenant Owner',
            'operations' => 'Operations Manager',
            'cx' => 'Customer Service',
            'inspector' => 'Inspector',
            'finance' => 'Finance',
        ];

        return view('marketing.demo', compact('tenants', 'roles'));
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tenant' => ['required', 'string'],
            'role' => ['required', 'in:owner,operations,cx,inspector,finance'],
        ]);

        $tenant = Tenant::query()
            ->where('slug', $validated['tenant'])
            ->orWhere('demo_key', $validated['tenant'])
            ->firstOrFail();

        $email = "{$validated['role']}@{$tenant->demo_key}.returns.test";

        $user = User::query()->where('email', $email)->firstOrFail();

        Auth::login($user, remember: true);

        return redirect()->to(url('/app/'.$tenant->slug));
    }
}
