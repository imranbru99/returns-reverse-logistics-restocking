<?php

declare(strict_types=1);

use App\Facades\Module;
use App\Models\StaffProfile;
use App\Models\User;
use App\Models\Warehouse;
use App\Services\People\StaffAttendanceSync;
use App\Support\TenantContext;
use ImranDevBd\AttendanceHub\Facades\AttendanceHub;

beforeEach(function (): void {
    Module::syncFromRegistry();
});

it('returns empty when attendance hub has no punch logs', function (): void {
    AttendanceHub::fake();

    $tenant = \App\Models\Tenant::factory()->create();
    $user = User::factory()->create();
    $warehouse = Warehouse::factory()->for($tenant)->create([
        'name' => 'Main DC',
        'code' => 'MAIN',
    ]);

    StaffProfile::query()->create([
        'tenant_id' => $tenant->getKey(),
        'user_id' => $user->getKey(),
        'warehouse_id' => $warehouse->getKey(),
        'job_title' => 'Receiver',
    ]);

    TenantContext::run($tenant, function () use ($tenant): void {
        Module::for($tenant)->enable('workforce-attendance');

        $windows = app(StaffAttendanceSync::class)->pullShiftWindows();

        expect($windows)->toHaveCount(0);
    });
});
