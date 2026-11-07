<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\IntegrationConnectionStatus;
use App\Enums\IntegrationDriver;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IntegrationConnection extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'tenant_id',
        'driver',
        'name',
        'credentials',
        'settings',
        'status',
        'last_synced_at',
    ];

    protected function casts(): array
    {
        return [
            'driver' => IntegrationDriver::class,
            'credentials' => 'encrypted:array',
            'settings' => 'array',
            'status' => IntegrationConnectionStatus::class,
            'last_synced_at' => 'datetime',
        ];
    }

    public function syncLogs(): HasMany
    {
        return $this->hasMany(IntegrationSyncLog::class);
    }
}
