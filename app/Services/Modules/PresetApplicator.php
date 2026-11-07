<?php

declare(strict_types=1);

namespace App\Services\Modules;

use App\Enums\BusinessPreset;
use App\Models\Tenant;

final class PresetApplicator
{
    public function __construct(private readonly ModuleManager $modules) {}

    public function apply(Tenant $tenant, BusinessPreset $preset): void
    {
        $manager = $this->modules->for($tenant);

        foreach ($preset->defaultModules() as $module) {
            $manager->enable($module);
        }

        $tenant->forceFill([
            'business_preset' => $preset,
        ])->save();
    }
}
