<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Enums\IntegrationConnectionStatus;
use App\Enums\IntegrationDriver;
use App\Filament\App\Resources\IntegrationConnectionResource\Pages;
use App\Filament\Concerns\HasModuleAccess;
use App\Models\IntegrationConnection;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class IntegrationConnectionResource extends Resource
{
    use HasModuleAccess;

    protected static ?string $model = IntegrationConnection::class;

    protected static ?string $module = 'integrations-hub';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static string|\UnitEnum|null $navigationGroup = 'Platform';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                TextInput::make('name')->maxLength(120),
                Select::make('driver')->options(collect(IntegrationDriver::cases())->mapWithKeys(fn (IntegrationDriver $d) => [$d->value => $d->label()])->all())->required(),
                Select::make('status')->options(collect(IntegrationConnectionStatus::cases())->mapWithKeys(fn (IntegrationConnectionStatus $s) => [$s->value => $s->label()])->all()),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('driver')->badge(),
                TextColumn::make('status')->badge(),
                TextColumn::make('last_synced_at')->dateTime(),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIntegrationConnections::route('/'),
            'create' => Pages\CreateIntegrationConnection::route('/create'),
            'edit' => Pages\EditIntegrationConnection::route('/{record}/edit'),
        ];
    }
}
