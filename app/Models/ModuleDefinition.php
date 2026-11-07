<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleDefinition extends Model
{
    protected $table = 'modules';

    public $incrementing = false;

    protected $primaryKey = 'key';

    protected $keyType = 'string';

    protected $fillable = [
        'key',
        'name',
        'description',
        'category',
        'dependencies',
        'icon',
        'price_tier',
        'default_enabled',
        'sort_order',
        'settings_schema',
        'is_core',
    ];

    protected function casts(): array
    {
        return [
            'dependencies' => 'array',
            'settings_schema' => 'array',
            'default_enabled' => 'boolean',
            'is_core' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
