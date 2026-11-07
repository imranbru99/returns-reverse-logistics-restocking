<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ShipmentResource\Pages;
use App\Filament\Concerns\HasModuleAccess;
use App\Models\Shipment;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Enums\ShipmentDirection;
use App\Enums\ShipmentStatus;

class ShipmentResource extends Resource
{
    use HasModuleAccess;

    protected static ?string $model = Shipment::class;

    protected static ?string $module = 'shipping-labels';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-truck';

    protected static string|\UnitEnum|null $navigationGroup = 'Shipping';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                Select::make('rma_id')->relationship('rma', 'number')->searchable()->preload(),
                Select::make('direction')->options(collect(ShipmentDirection::cases())->mapWithKeys(fn ($c) => [$c->value => $c->label()])->all())->required(),
                TextInput::make('carrier')->required(),
                TextInput::make('tracking_number'),
                Select::make('status')->options(collect(ShipmentStatus::cases())->mapWithKeys(fn ($c) => [$c->value => $c->label()])->all())->required(),
                TextInput::make('rate_cents')->numeric(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rma.number')->label('RMA'),
                TextColumn::make('direction')->badge(),
                TextColumn::make('tracking_number')->searchable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('carrier'),
            ])
            ->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShipments::route('/'),
            'create' => Pages\CreateShipment::route('/create'),
            'edit' => Pages\EditShipment::route('/{record}/edit'),
        ];
    }
}
