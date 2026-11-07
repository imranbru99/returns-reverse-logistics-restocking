<?php

declare(strict_types=1);

namespace App\Filament\SuperAdmin\Resources\BlogPostResource\Pages;

use App\Filament\SuperAdmin\Resources\BlogPostResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBlogPosts extends ListRecords
{
    protected static string $resource = BlogPostResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
