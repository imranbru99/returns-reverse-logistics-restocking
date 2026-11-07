<?php

declare(strict_types=1);

namespace App\Enums;

enum BusinessPreset: string
{
    case EcommerceStore = 'ecommerce_store';
    case FashionApparel = 'fashion_apparel';
    case Electronics = 'electronics';
    case Marketplace = 'marketplace';
    case ThreePL = '3pl_returns_processor';
    case Refurbisher = 'refurbisher_reseller';
    case Liquidator = 'liquidator';
    case Manufacturer = 'manufacturer';
    case RetailChain = 'retail_chain';

    public function label(): string
    {
        return match ($this) {
            self::EcommerceStore => 'E-commerce Store',
            self::FashionApparel => 'Fashion / Apparel',
            self::Electronics => 'Electronics',
            self::Marketplace => 'Marketplace',
            self::ThreePL => '3PL / Returns Processor',
            self::Refurbisher => 'Refurbisher / Reseller',
            self::Liquidator => 'Liquidator',
            self::Manufacturer => 'Manufacturer',
            self::RetailChain => 'Retail Chain',
        };
    }

    /**
     * Module keys enabled by default for this business type (matches config/modules.php registry).
     *
     * @return list<string>
     */
    public function defaultModules(): array
    {
        $core = [
            'tenant-organization',
            'users-roles-permissions',
            'settings-customization',
            'audit-log-compliance',
            'notifications-center',
            'documents-pdf-studio',
            'product-catalog',
            'inventory-management',
            'warehouse-management',
            'orders-customers',
            'return-policy-engine',
            'rma-management',
            'customer-returns-portal',
            'shipping-labels',
            'receiving-dock',
            'inspection-engine',
            'disposition-engine',
            'restocking-putaway',
            'refunds-credits',
            'analytics-reporting',
            'api-webhooks',
            'localization',
            'backup-system-health',
        ];

        return match ($this) {
            self::EcommerceStore => array_values(array_unique(array_merge($core, [
                'exchanges-store-credit',
                'drop-off-locations',
                'fraud-abuse-prevention',
                'customer-support-desk',
                'knowledge-base',
                'integrations-hub',
                'billing-subscriptions',
            ]))),
            self::FashionApparel => array_values(array_unique(array_merge($core, [
                'exchanges-store-credit',
                'fraud-abuse-prevention',
                'resale-marketplace',
                'customer-support-desk',
                'knowledge-base',
                'integrations-hub',
                'billing-subscriptions',
            ]))),
            self::Electronics => array_values(array_unique(array_merge($core, [
                'ai-inspection-assist',
                'refurbishment-repair',
                'rtv-vendor-claims',
                'warranty-claims',
                'resale-marketplace',
                'fraud-abuse-prevention',
                'customer-support-desk',
                'integrations-hub',
                'billing-subscriptions',
            ]))),
            self::Marketplace => array_values(array_unique(array_merge($core, [
                'exchanges-store-credit',
                'fraud-abuse-prevention',
                'resale-marketplace',
                'liquidation-auctions',
                'ai-insights-forecasting',
                'integrations-hub',
                'billing-subscriptions',
            ]))),
            self::ThreePL => array_values(array_unique(array_merge($core, [
                'drop-off-locations',
                'ai-inspection-assist',
                'refurbishment-repair',
                'rtv-vendor-claims',
                'workforce-attendance',
                'tasks-work-queues',
                'finance-cost-accounting',
                'recycling-sustainability',
                'integrations-hub',
                'billing-subscriptions',
                'executive-dashboards',
            ]))),
            self::Refurbisher => array_values(array_unique(array_merge($core, [
                'ai-inspection-assist',
                'refurbishment-repair',
                'resale-marketplace',
                'warranty-claims',
                'finance-cost-accounting',
                'workforce-attendance',
                'tasks-work-queues',
                'billing-subscriptions',
            ]))),
            self::Liquidator => array_values(array_unique(array_merge($core, [
                'liquidation-auctions',
                'resale-marketplace',
                'recycling-sustainability',
                'finance-cost-accounting',
                'workforce-attendance',
                'billing-subscriptions',
            ]))),
            self::Manufacturer => array_values(array_unique(array_merge($core, [
                'rtv-vendor-claims',
                'warranty-claims',
                'fraud-abuse-prevention',
                'finance-cost-accounting',
                'ai-insights-forecasting',
                'integrations-hub',
            ]))),
            self::RetailChain => array_values(array_unique(array_merge($core, [
                'exchanges-store-credit',
                'drop-off-locations',
                'fraud-abuse-prevention',
                'warranty-claims',
                'customer-support-desk',
                'knowledge-base',
                'workforce-attendance',
                'integrations-hub',
                'billing-subscriptions',
                'executive-dashboards',
            ]))),
        };
    }
}
