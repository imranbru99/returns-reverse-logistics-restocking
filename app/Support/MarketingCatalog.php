<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\BusinessPreset;

final class MarketingCatalog
{
    /**
     * @return list<array<string, mixed>>
     */
    public static function features(): array
    {
        return config('features.features', []);
    }

    public static function feature(string $key): ?array
    {
        foreach (self::features() as $feature) {
            if (($feature['key'] ?? '') === $key) {
                return $feature;
            }
        }

        return null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function modules(): array
    {
        return config('modules.registry', []);
    }

    public static function module(string $key): ?array
    {
        foreach (self::modules() as $module) {
            if (($module['key'] ?? '') === $key) {
                return $module;
            }
        }

        return null;
    }

    /**
     * @return array<string, string>
     */
    public static function industries(): array
    {
        return [
            'fashion' => 'Fashion & apparel',
            'electronics' => 'Electronics',
            'marketplace' => 'Marketplace',
            '3pl' => '3PL & returns processor',
            'refurbisher' => 'Refurbisher & reseller',
            'liquidator' => 'Liquidator',
            'retail' => 'Retail chain',
        ];
    }

    public static function industryPreset(string $industry): ?BusinessPreset
    {
        return match ($industry) {
            'fashion' => BusinessPreset::FashionApparel,
            'electronics' => BusinessPreset::Electronics,
            'marketplace' => BusinessPreset::Marketplace,
            '3pl' => BusinessPreset::ThreePL,
            'refurbisher' => BusinessPreset::Refurbisher,
            'liquidator' => BusinessPreset::Liquidator,
            'retail' => BusinessPreset::RetailChain,
            default => null,
        };
    }

    /**
     * @return array<string, string>
     */
    public static function roles(): array
    {
        return [
            'warehouse' => 'Warehouse & fulfillment',
            'cx' => 'Customer experience',
            'finance' => 'Finance & accounting',
            'operations' => 'Operations',
            'executives' => 'Executives',
        ];
    }

    /**
     * @return list<string>
     */
    public static function roleModuleKeys(string $role): array
    {
        return match ($role) {
            'warehouse' => ['warehouse-management', 'receiving-dock', 'restocking-putaway', 'shipping-labels'],
            'cx' => ['customer-returns-portal', 'customer-support-desk', 'rma-management', 'knowledge-base'],
            'finance' => ['finance-cost-accounting', 'refunds-credits', 'billing-subscriptions'],
            'operations' => ['tasks-work-queues', 'rma-management', 'inspection-engine', 'disposition-engine'],
            'executives' => ['executive-dashboards', 'analytics-reporting', 'ai-insights-forecasting'],
            default => [],
        };
    }

    /**
     * @return list<array{title: string, body: string}>
     */
    public static function helpArticles(): array
    {
        return [
            ['title' => 'Start a return', 'body' => 'Use the customer portal with your order number and email to open an RMA.'],
            ['title' => 'Track RMA status', 'body' => 'Enter your RMA number and email on the track page to see workflow progress.'],
            ['title' => 'Module marketplace', 'body' => 'Tenant owners enable modules from the app panel; dependencies resolve automatically.'],
            ['title' => 'API authentication', 'body' => 'Issue Sanctum tokens per user and send X-Tenant-ID on each /api/v1 request.'],
        ];
    }
}
