<?php

declare(strict_types=1);

namespace App\Services\Disposition;

use App\Enums\DispositionType;
use App\Enums\Grade;
use App\Models\Inspection;
use App\Models\Product;

final class DispositionRouter
{
    /**
     * @return array{type: DispositionType, reason: string, confidence: float}
     */
    public function suggest(Inspection $inspection, ?Product $product = null): array
    {
        $product ??= $inspection->product;
        $grade = $inspection->grade ?? Grade::C;
        $margin = $this->marginPercent($product);
        $categorySlug = $product?->category?->slug;

        $type = match ($grade) {
            Grade::A => $margin >= 25
                ? DispositionType::RestockAsNew
                : DispositionType::RestockOpenBox,
            Grade::B => DispositionType::RestockOpenBox,
            Grade::C => DispositionType::Refurbish,
            Grade::D => $margin >= 20
                ? DispositionType::Resell
                : DispositionType::Liquidate,
            Grade::Scrap => $categorySlug === 'electronics'
                ? DispositionType::Recycle
                : DispositionType::Destroy,
        };

        if ($grade === Grade::C && $margin < 10) {
            $type = DispositionType::Liquidate;
        }

        return [
            'type' => $type,
            'reason' => sprintf('Grade %s with estimated margin %.1f%%', $grade->value, $margin),
            'confidence' => $grade === Grade::A || $grade === Grade::Scrap ? 0.92 : 0.78,
        ];
    }

    private function marginPercent(?Product $product): float
    {
        if ($product === null) {
            return 30.0;
        }

        $meta = $product->meta ?? [];

        if (isset($meta['margin_percent'])) {
            return (float) $meta['margin_percent'];
        }

        if (isset($meta['cost_cents'], $product->price) && (float) $product->price > 0) {
            $cost = ((float) $meta['cost_cents']) / 100;
            $price = (float) $product->price;

            return round((($price - $cost) / $price) * 100, 2);
        }

        return 30.0;
    }
}
