<?php

declare(strict_types=1);

use App\Models\Tenant;
use App\Models\User;
use Database\Seeders\DemoSeeder;
use Database\Seeders\ModuleSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Facades\Artisan;

it('registers the demo reset command', function (): void {
    expect(Artisan::all())->toHaveKey('demo:reset');
});

it('seeds demo tenants and role users', function (): void {
    $this->seed(ModuleSeeder::class);
    $this->seed(RolePermissionSeeder::class);
    $this->seed(DemoSeeder::class);

    expect(Tenant::query()->where('demo_key', 'fashion')->exists())->toBeTrue()
        ->and(User::query()->where('email', 'owner@fashion.returns.test')->exists())->toBeTrue();
})->group('slow');
