<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\KnowledgeArticleResource\Pages;

use App\Filament\App\Resources\KnowledgeArticleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKnowledgeArticle extends CreateRecord
{
    protected static string $resource = KnowledgeArticleResource::class;
}
