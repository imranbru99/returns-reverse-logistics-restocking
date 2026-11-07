<?php

declare(strict_types=1);

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Support\MarketingCatalog;
use Illuminate\Http\Response;

final class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $base = rtrim((string) config('app.url'), '/');
        $urls = [
            $base.'/',
            $base.'/features',
            $base.'/how-it-works',
            $base.'/modules',
            $base.'/pricing',
            $base.'/integrations',
            $base.'/roi-calculator',
            $base.'/sustainability-calculator',
            $base.'/track',
            $base.'/returns',
            $base.'/portal',
            $base.'/developers',
            $base.'/docs/api',
            $base.'/help',
            $base.'/contact',
            $base.'/demo',
            $base.'/register',
        ];

        foreach (MarketingCatalog::features() as $feature) {
            $urls[] = $base.'/features/'.($feature['key'] ?? '');
        }

        foreach (array_keys(MarketingCatalog::industries()) as $industry) {
            $urls[] = $base.'/solutions/'.$industry;
        }

        foreach (array_keys(MarketingCatalog::roles()) as $role) {
            $urls[] = $base.'/solutions/role/'.$role;
        }

        $xml = view('marketing.sitemap', compact('urls'))->render();

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
