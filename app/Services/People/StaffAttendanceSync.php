<?php

declare(strict_types=1);

namespace App\Services\People;

use App\Enums\ShiftStatus;
use App\Models\Shift;
use App\Models\StaffProfile;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

/**
 * Syncs warehouse shift windows from Attendance Hub punch data when the package is installed.
 *
 * @see docs/ATTENDANCE_HUB.md
 */
final class StaffAttendanceSync
{
    /**
     * @return Collection<int, array{staff_profile_id: string, starts_at: string, ends_at: string}>
     */
    public function pullShiftWindows(?CarbonInterface $since = null): Collection
    {
        if (! class_exists(\ImranDevBd\AttendanceHub\Facades\AttendanceHub::class)) {
            return collect();
        }

        $since ??= now()->subDay();
        $windows = collect();

        $profiles = StaffProfile::query()->with('user')->get();

        foreach ($profiles as $profile) {
            try {
                $logs = \ImranDevBd\AttendanceHub\Facades\AttendanceHub::provider('virtual')
                    ->pullLogs($since, ['employee_code' => (string) $profile->user_id]);

                if ($logs === [] || $logs === null) {
                    continue;
                }

                $first = $logs[0]['punched_at'] ?? $since->toIso8601String();
                $last = $logs[count($logs) - 1]['punched_at'] ?? now()->toIso8601String();

                Shift::query()->updateOrCreate(
                    [
                        'staff_profile_id' => $profile->getKey(),
                        'starts_at' => $first,
                    ],
                    [
                        'tenant_id' => $profile->tenant_id,
                        'warehouse_id' => $profile->warehouse_id,
                        'ends_at' => $last,
                        'status' => ShiftStatus::Completed,
                        'meta' => ['source' => 'attendance_hub'],
                    ],
                );

                $windows->push([
                    'staff_profile_id' => $profile->getKey(),
                    'starts_at' => $first,
                    'ends_at' => $last,
                ]);
            } catch (\Throwable) {
                continue;
            }
        }

        return $windows;
    }
}
