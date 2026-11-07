<?php

declare(strict_types=1);

it('renders the features index from config', function (): void {
    $first = config('features.features.0.name');

    $this->get('/features')
        ->assertOk()
        ->assertSee('Features')
        ->assertSee(htmlspecialchars($first, ENT_QUOTES), false);
});

it('renders a feature detail page', function (): void {
    $key = (string) config('features.features.0.key');

    $name = (string) config('features.features.0.name');

    $this->get('/features/'.$key)
        ->assertOk()
        ->assertSee(htmlspecialchars($name, ENT_QUOTES), false);
});
