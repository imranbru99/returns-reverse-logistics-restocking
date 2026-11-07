<?php

declare(strict_types=1);

namespace App\Enums;

enum IntegrationDriver: string
{
    case Shopify = 'shopify';
    case WooCommerce = 'woocommerce';
    case Magento = 'magento';
    case BigCommerce = 'bigcommerce';
    case Csv = 'csv';
    case Custom = 'custom';

    public function label(): string
    {
        return match ($this) {
            self::Shopify => 'Shopify',
            self::WooCommerce => 'WooCommerce',
            self::Magento => 'Magento',
            self::BigCommerce => 'BigCommerce',
            self::Csv => 'CSV import',
            self::Custom => 'Custom',
        };
    }
}
