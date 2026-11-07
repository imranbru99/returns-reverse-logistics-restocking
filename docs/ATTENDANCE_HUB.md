# Attendance Hub integration

The **workforce-attendance** module uses [Laravel Attendance Hub](https://github.com/imrandevbd/laravel-attendance-hub) to normalize punch data from ZKTeco, ADMS push, and virtual sandbox devices.

## Application service

`App\Services\People\StaffAttendanceSync` pulls punch windows for each `StaffProfile` and upserts `Shift` rows:

```php
use App\Services\People\StaffAttendanceSync;

$windows = app(StaffAttendanceSync::class)->pullShiftWindows(now()->subDay());
```

When Attendance Hub is not installed, the service returns an empty collection without error.

## Facade usage

```php
use ImranDevBd\AttendanceHub\Facades\AttendanceHub;

AttendanceHub::fake(); // in Pest
AttendanceHub::provider('virtual')->pullLogs($since, ['employee_code' => $userId]);
```

See `config/attendance-hub.php` and `docs/PACKAGES.md` for driver configuration.
