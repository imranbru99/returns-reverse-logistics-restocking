<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\SupportTicketResource\Pages;
use App\Filament\Concerns\HasModuleAccess;
use App\Models\SupportTicket;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SupportTicketResource extends Resource
{
    use HasModuleAccess;

    protected static ?string $model = SupportTicket::class;

    protected static ?string $module = 'customer-support-desk';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-lifebuoy';

    protected static string|\UnitEnum|null $navigationGroup = 'People';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')->searchable(),
                TextColumn::make('subject')->searchable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('csat_score')->label('CSAT'),
            ])
            ->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSupportTickets::route('/'),
            'create' => Pages\CreateSupportTicket::route('/create'),
            'edit' => Pages\EditSupportTicket::route('/{record}/edit'),
        ];
    }
}
