<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\MarketplaceListingResource\Pages;
use App\Filament\Concerns\HasModuleAccess;
use App\Models\MarketplaceListing;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MarketplaceListingResource extends Resource
{
    use HasModuleAccess;

    protected static ?string $model = MarketplaceListing::class;

    protected static ?string $module = 'resale-marketplace';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-storefront';

    protected static string|\UnitEnum|null $navigationGroup = 'Revenue';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')->label('Product'),
                TextColumn::make('grade')->searchable(),
                TextColumn::make('price_cents')->label('Price (¢)'),
                TextColumn::make('status')->badge(),
            ])
            ->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMarketplaceListings::route('/'),
            'create' => Pages\CreateMarketplaceListing::route('/create'),
            'edit' => Pages\EditMarketplaceListing::route('/{record}/edit'),
        ];
    }
}
