<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class ReturnPolicy extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use HasUlids;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'return_window_days',
        'requires_approval',
        'restocking_fee_percent',
        'is_default',
        'is_active',
        'rules',
    ];

    protected function casts(): array
    {
        return [
            'requires_approval' => 'boolean',
            'restocking_fee_percent' => 'decimal:2',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
            'rules' => 'array',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function rmas(): HasMany
    {
        return $this->hasMany(Rma::class);
    }
}
