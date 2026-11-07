<?php

declare(strict_types=1);

namespace App\Filament\SuperAdmin\Resources\CmsPageResource\Pages;

use App\Filament\SuperAdmin\Resources\CmsPageResource;
use Filament\Resources\Pages\EditRecord;

class EditCmsPage extends EditRecord
{
    protected static string $resource = CmsPageResource::class;
}
