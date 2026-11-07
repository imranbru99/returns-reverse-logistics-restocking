<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CarrierDriver;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarrierAccount extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'driver',
        'name',
        'credentials',
        'is_default',
        'sandbox',
    ];

    protected function casts(): array
    {
        return [
            'driver' => CarrierDriver::class,
            'credentials' => 'encrypted:array',
            'is_default' => 'boolean',
            'sandbox' => 'boolean',
        ];
    }
}
