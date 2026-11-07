<?php

declare(strict_types=1);

it('renders the marketing home page', function (): void {
    $this->get('/')->assertOk()->assertSee(config('returns.brand.name'), false);
});
