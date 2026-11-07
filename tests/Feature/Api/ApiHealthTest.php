<?php

declare(strict_types=1);

it('returns api health payload', function (): void {
    $this->getJson('/api/v1/health')
        ->assertOk()
        ->assertJsonPath('status', 'ok')
        ->assertJsonPath('version', 'v1');
});
