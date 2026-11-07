<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Enums\CarrierDriver;
use App\Filament\App\Resources\CarrierAccountResource\Pages;
use App\Filament\Concerns\HasModuleAccess;
use App\Models\CarrierAccount;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CarrierAccountResource extends Resource
{
    use HasModuleAccess;

    protected static ?string $model = CarrierAccount::class;

    protected static ?string $module = 'shipping-labels';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-storefront';

    protected static string|\UnitEnum|null $navigationGroup = 'Shipping';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                TextInput::make('name')->required(),
                Select::make('driver')->options(collect(CarrierDriver::cases())->mapWithKeys(fn ($c) => [$c->value => $c->label()])->all())->required(),
                Toggle::make('is_default'),
                Toggle::make('sandbox')->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('driver')->badge(),
                IconColumn::make('is_default')->boolean(),
                IconColumn::make('sandbox')->boolean(),
            ])
            ->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCarrierAccounts::route('/'),
            'create' => Pages\CreateCarrierAccount::route('/create'),
            'edit' => Pages\EditCarrierAccount::route('/{record}/edit'),
        ];
    }
}
