<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserType;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasAvatar, HasName, HasTenants
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens;

    use HasFactory;
    use HasRoles;
    use LogsActivity;
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'phone',
        'locale',
        'timezone',
        'is_platform_admin',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'last_login_at',
        'last_login_ip',
        'preferences',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'user_type' => UserType::class,
            'is_platform_admin' => 'boolean',
            'two_factor_confirmed_at' => 'datetime',
            'last_login_at' => 'datetime',
            'preferences' => 'array',
            'two_factor_recovery_codes' => 'encrypted:array',
            'two_factor_secret' => 'encrypted',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'user_type', 'phone', 'locale', 'timezone', 'is_platform_admin'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class, 'tenant_user')
            ->withPivot(['role', 'is_owner', 'joined_at', 'invited_at', 'last_login_at'])
            ->withTimestamps();
    }

    public function getTenants(Panel $panel): Collection
    {
        if ($panel->getId() === 'super-admin') {
            return collect();
        }

        return $this->tenants()->where('is_active', true)->get();
    }

    public function canAccessTenant(Model $tenant): bool
    {
        if ($this->is_platform_admin) {
            return true;
        }

        return $this->tenants()->whereKey($tenant->getKey())->exists();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'super-admin' => $this->is_platform_admin
                || $this->user_type === UserType::PlatformSuperAdmin
                || $this->user_type === UserType::PlatformSupport,
            'app', 'warehouse', 'merchant', 'vendor' => $this->tenants()->exists() || $this->is_platform_admin,
            default => true,
        };
    }

    public function getFilamentName(): string
    {
        return $this->name;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return null;
    }

    public function isPlatformUser(): bool
    {
        return $this->is_platform_admin || ($this->user_type?->isPlatform() ?? false);
    }

    public function belongsToTenant(Tenant|string $tenant): bool
    {
        $id = $tenant instanceof Tenant ? $tenant->getKey() : $tenant;

        return $this->tenants()->whereKey($id)->exists();
    }
}
