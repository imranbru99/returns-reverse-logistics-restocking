<?php

declare(strict_types=1);

use App\Models\User;
use Filament\Facades\Filament;

it('allows platform admins into the super-admin panel', function (): void {
    $admin = User::factory()->platformAdmin()->create();
    $panel = Filament::getPanel('super-admin');

    expect($admin->canAccessPanel($panel))->toBeTrue();
});

it('denies regular tenant users the super-admin panel', function (): void {
    $user = User::factory()->create();
    $panel = Filament::getPanel('super-admin');

    expect($user->canAccessPanel($panel))->toBeFalse();
});
