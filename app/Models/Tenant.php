<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BusinessPreset;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use ImranDev\UniversalSlug\SlugOptions;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Tenant extends Model implements HasAvatar, HasMedia, HasName
{
    use HasFactory;
    use HasUlids;
    use HasUniversalSlug;
    use InteractsWithMedia;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'legal_name',
        'email',
        'phone',
        'website',
        'domain',
        'subdomain',
        'business_preset',
        'timezone',
        'locale',
        'currency',
        'country_code',
        'branding',
        'settings',
        'trial_ends_at',
        'is_active',
        'onboarding_completed_at',
        'demo_key',
    ];

    protected function casts(): array
    {
        return [
            'business_preset' => BusinessPreset::class,
            'branding' => 'array',
            'settings' => 'array',
            'trial_ends_at' => 'datetime',
            'onboarding_completed_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function getFilamentName(): string
    {
        return $this->name;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->getFirstMediaUrl('logo') ?: null;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
        $this->addMediaCollection('favicon')->singleFile();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tenant_user')
            ->withPivot(['role', 'is_owner', 'joined_at', 'invited_at', 'last_login_at'])
            ->withTimestamps();
    }

    public function modules(): HasMany
    {
        return $this->hasMany(TenantModule::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public function isModuleEnabled(string $key): bool
    {
        return $this->modules()
            ->where('module', $key)
            ->where('enabled', true)
            ->exists();
    }

    public function primaryColor(): string
    {
        return (string) data_get($this->branding, 'primary_color', '#0F766E');
    }

    public function brandingSettings(): \App\Settings\TenantBrandingSettings
    {
        return \App\Settings\TenantBrandingSettings::for($this);
    }

    /**
     * @param  array<string, mixed>  $values
     */
    public function mergeSettings(array $values): void
    {
        $this->forceFill([
            'settings' => array_merge($this->settings ?? [], $values),
        ])->save();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
