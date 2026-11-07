<?php

declare(strict_types=1);

namespace App\Filament\SuperAdmin\Resources\BlogPostResource\Pages;

use App\Filament\SuperAdmin\Resources\BlogPostResource;
use Filament\Resources\Pages\EditRecord;

class EditBlogPost extends EditRecord
{
    protected static string $resource = BlogPostResource::class;
}
