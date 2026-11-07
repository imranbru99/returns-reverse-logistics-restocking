<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

class KnowledgeArticle extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use HasUlids;
    use HasUniversalSlug;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'title',
        'slug',
        'locale',
        'body',
        'is_public',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'meta' => 'array',
        ];
    }
}
