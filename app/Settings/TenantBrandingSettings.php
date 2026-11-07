<?php

declare(strict_types=1);

namespace App\Settings;

use App\Models\Tenant;

/**
 * Tenant-scoped branding helpers backed by the tenant `branding` JSON column.
 * Spatie laravel-settings remains available for platform-wide defaults via the settings table.
 */
final class TenantBrandingSettings
{
    public function __construct(
        private Tenant $tenant,
    ) {}

    public static function for(Tenant $tenant): self
    {
        return new self($tenant);
    }

    public function primaryColor(): string
    {
        return (string) data_get($this->tenant->branding, 'primary_color', '#0F766E');
    }

    public function secondaryColor(): string
    {
        return (string) data_get($this->tenant->branding, 'secondary_color', '#134E4A');
    }

    public function tagline(): ?string
    {
        $tagline = data_get($this->tenant->branding, 'tagline');

        return is_string($tagline) ? $tagline : null;
    }

    /**
     * @param  array<string, mixed>  $values
     */
    public function update(array $values): void
    {
        $branding = array_merge($this->tenant->branding ?? [], $values);
        $this->tenant->forceFill(['branding' => $branding])->save();
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'primary_color' => $this->primaryColor(),
            'secondary_color' => $this->secondaryColor(),
            'tagline' => $this->tagline(),
        ];
    }
}
