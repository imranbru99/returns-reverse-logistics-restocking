<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

final class PlanSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('plans.plans', []) as $key => $plan) {
            Plan::query()->updateOrCreate(
                ['key' => $key],
                [
                    'name' => $plan['name'] ?? ucfirst($key),
                    'price' => $plan['price'],
                    'currency' => config('plans.currency', 'USD'),
                    'limits' => $plan['limits'] ?? [],
                    'included_modules' => $plan['included_modules'] ?? [],
                    'is_active' => true,
                ],
            );
        }
    }
}
