<?php

declare(strict_types=1);

namespace App\Services\Catalog;

use App\Models\Product;
use App\Models\Tenant;

final class CreateProductAction
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function execute(Tenant $tenant, array $attributes): Product
    {
        $attributes['tenant_id'] = $tenant->getKey();

        if (! isset($attributes['sku'])) {
            $attributes['sku'] = strtoupper(uniqid('SKU-'));
        }

        return Product::query()->create($attributes);
    }
}
