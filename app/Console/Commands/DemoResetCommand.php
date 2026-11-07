<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Database\Seeders\DemoSeeder;
use Database\Seeders\ModuleSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Console\Command;

final class DemoResetCommand extends Command
{
    protected $signature = 'demo:reset
                            {--heavy : Seed larger product/order/RMA volumes (500+ records)}
                            {--full : Alias for --heavy legacy flag}';

    protected $description = 'Reset the database and seed demo tenants, catalog, orders, and RMAs.';

    public function handle(): int
    {
        if ($this->option('heavy') || $this->option('full')) {
            config([
                'returns.demo_seed.products' => 500,
                'returns.demo_seed.orders' => 300,
                'returns.demo_seed.rmas' => 200,
            ]);
        }

        $this->call('migrate:fresh', ['--force' => true]);

        $this->call(ModuleSeeder::class);
        $this->call(RolePermissionSeeder::class);
        $this->call(DemoSeeder::class);

        $this->components->info('Demo environment reset complete.');

        return self::SUCCESS;
    }
}
