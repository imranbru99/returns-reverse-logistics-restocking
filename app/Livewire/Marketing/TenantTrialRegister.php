<?php

declare(strict_types=1);

namespace App\Livewire\Marketing;

use App\Enums\BusinessPreset;
use App\Enums\UserType;
use App\Facades\Module;
use App\Models\Tenant;
use App\Models\User;
use App\Services\Modules\PresetApplicator;
use App\Support\TenantContext;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.marketing')]
final class TenantTrialRegister extends Component
{
    public string $company = '';

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $preset = 'fashion_apparel';

    public function register(): void
    {
        $validated = $this->validate([
            'company' => ['required', 'string', 'max:160'],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'preset' => ['required', 'in:'.implode(',', array_column(BusinessPreset::cases(), 'value'))],
        ]);

        $slug = Str::slug($validated['company']);
        $slug = Tenant::query()->where('slug', $slug)->exists() ? $slug.'-'.Str::lower(Str::random(4)) : $slug;

        $tenant = Tenant::query()->create([
            'name' => $validated['company'],
            'slug' => $slug,
            'email' => $validated['email'],
            'trial_ends_at' => now()->addDays(14),
            'is_active' => true,
            'locale' => app()->getLocale(),
            'currency' => 'USD',
        ]);

        Module::syncFromRegistry();
        app(PresetApplicator::class)->apply($tenant, BusinessPreset::from($validated['preset']));

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'user_type' => UserType::TenantOwner,
            'email_verified_at' => now(),
        ]);

        $user->tenants()->attach($tenant, [
            'role' => UserType::TenantOwner->value,
            'is_owner' => true,
            'joined_at' => now(),
        ]);

        TenantContext::run($tenant, function () use ($tenant, $user): void {
            setPermissionsTeamId($tenant->getKey());
            $user->assignRole(UserType::TenantOwner->value);
        });

        Auth::login($user);

        $this->redirect(url('/app/'.$tenant->slug));
    }

    public function render()
    {
        return view('livewire.marketing.tenant-trial-register', [
            'presets' => BusinessPreset::cases(),
        ]);
    }
}
