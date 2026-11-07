<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Services\Modules\ModuleManager;
use Illuminate\Database\Seeder;

final class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        app(ModuleManager::class)->syncFromRegistry();
    }
}
