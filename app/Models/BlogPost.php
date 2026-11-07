<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use ImranDev\UniversalSlug\SlugOptions;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

class BlogPost extends Model
{
    use HasFactory;
    use HasUlids;
    use HasUniversalSlug;

    protected $fillable = [
        'tenant_id',
        'title',
        'slug',
        'excerpt',
        'body',
        'published_at',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished($query): void
    {
        $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }
}
