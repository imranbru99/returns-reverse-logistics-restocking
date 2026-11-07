<?php

declare(strict_types=1);

use App\Models\CmsPage;

it('generates a universal slug from the page title', function (): void {
    $page = CmsPage::factory()->create([
        'title' => 'Returns Policy Overview',
    ]);

    expect($page->slug)->toBe('returns-policy-overview');
});
