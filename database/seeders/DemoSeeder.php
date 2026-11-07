<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\RmaStatus;
use App\Enums\UserType;
use App\Facades\Module;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Rma;
use App\Models\Tenant;
use App\Models\User;
use App\Services\Modules\PresetApplicator;
use App\Support\TenantContext;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class DemoSeeder extends Seeder
{
    /**
     * @var list<array{slug: string, name: string, preset: \App\Enums\BusinessPreset, demo_key: string}>
     */
    private const TENANTS = [
        ['slug' => 'fashion', 'name' => 'Fashion Demo Co', 'preset' => \App\Enums\BusinessPreset::FashionApparel, 'demo_key' => 'fashion'],
        ['slug' => 'electronics', 'name' => 'Electronics Demo Co', 'preset' => \App\Enums\BusinessPreset::Electronics, 'demo_key' => 'electronics'],
        ['slug' => 'three-pl', 'name' => 'Three PL Demo', 'preset' => \App\Enums\BusinessPreset::ThreePL, 'demo_key' => 'three-pl'],
        ['slug' => 'liquidator', 'name' => 'Liquidator Demo', 'preset' => \App\Enums\BusinessPreset::Liquidator, 'demo_key' => 'liquidator'],
    ];

    /**
     * @var list<array{role: UserType, email_local: string}>
     */
    private const ROLES = [
        ['role' => UserType::TenantOwner, 'email_local' => 'owner'],
        ['role' => UserType::OperationsManager, 'email_local' => 'operations'],
        ['role' => UserType::CustomerServiceAgent, 'email_local' => 'cx'],
        ['role' => UserType::Inspector, 'email_local' => 'inspector'],
        ['role' => UserType::Finance, 'email_local' => 'finance'],
    ];

    public function run(): void
    {
        Module::syncFromRegistry();

        $password = (string) config('returns.demo_password', 'DemoPass123!');
        $productCount = (int) config('returns.demo_seed.products', 100);
        $orderCount = (int) config('returns.demo_seed.orders', 80);
        $rmaCount = (int) config('returns.demo_seed.rmas', 40);

        $perTenantProducts = (int) max(1, floor($productCount / count(self::TENANTS)));
        $perTenantOrders = (int) max(1, floor($orderCount / count(self::TENANTS)));
        $perTenantRmas = (int) max(1, floor($rmaCount / count(self::TENANTS)));

        foreach (self::TENANTS as $definition) {
            $tenant = Tenant::query()->create([
                'name' => $definition['name'],
                'slug' => $definition['slug'],
                'subdomain' => $definition['slug'],
                'demo_key' => $definition['demo_key'],
                'email' => "hello@{$definition['demo_key']}.returns.test",
                'is_active' => true,
                'locale' => 'en',
                'currency' => 'USD',
            ]);

            app(PresetApplicator::class)->apply($tenant, $definition['preset']);

            foreach (self::ROLES as $roleDef) {
                $email = "{$roleDef['email_local']}@{$definition['demo_key']}.returns.test";

                $user = User::query()->firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => $roleDef['role']->label(),
                        'password' => Hash::make($password),
                        'user_type' => $roleDef['role'],
                        'email_verified_at' => now(),
                    ],
                );

                $user->tenants()->syncWithoutDetaching([
                    $tenant->getKey() => [
                        'role' => $roleDef['role']->value,
                        'is_owner' => $roleDef['role'] === UserType::TenantOwner,
                        'joined_at' => now(),
                    ],
                ]);

                TenantContext::run($tenant, function () use ($tenant, $user, $roleDef): void {
                    setPermissionsTeamId($tenant->getKey());
                    $user->syncRoles([$roleDef['role']->value]);
                });
            }

            TenantContext::run($tenant, function () use ($tenant, $perTenantProducts, $perTenantOrders, $perTenantRmas): void {
                Product::factory()->count($perTenantProducts)->create([
                    'tenant_id' => $tenant->getKey(),
                ]);

                $orders = Order::factory()
                    ->count($perTenantOrders)
                    ->create(['tenant_id' => $tenant->getKey()]);

                foreach ($orders as $order) {
                    $products = Product::query()->where('tenant_id', $tenant->getKey())->inRandomOrder()->limit(3)->get();

                    foreach ($products as $product) {
                        OrderItem::factory()->create([
                            'tenant_id' => $tenant->getKey(),
                            'order_id' => $order->getKey(),
                            'product_id' => $product->getKey(),
                        ]);
                    }
                }

                $statuses = RmaStatus::cases();
                $ordersForRma = Order::query()->where('tenant_id', $tenant->getKey())->inRandomOrder()->limit($perTenantRmas)->get();

                foreach ($ordersForRma as $index => $order) {
                    $status = $statuses[$index % count($statuses)];

                    Rma::factory()->create([
                        'tenant_id' => $tenant->getKey(),
                        'customer_id' => $order->customer_id,
                        'order_id' => $order->getKey(),
                        'status' => $status,
                    ]);
                }
            });
        }
    }
}
