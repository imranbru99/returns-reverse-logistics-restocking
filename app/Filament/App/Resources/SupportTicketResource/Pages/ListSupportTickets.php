<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\SupportTicketResource\Pages;

use App\Filament\App\Resources\SupportTicketResource;
use Filament\Resources\Pages\ListRecords;

class ListSupportTickets extends ListRecords
{
    protected static string $resource = SupportTicketResource::class;
}
