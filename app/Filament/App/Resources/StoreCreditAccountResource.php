<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\StoreCreditAccountResource\Pages;
use App\Filament\Concerns\HasModuleAccess;
use App\Models\StoreCreditAccount;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StoreCreditAccountResource extends Resource
{
    use HasModuleAccess;

    protected static ?string $model = StoreCreditAccount::class;

    protected static ?string $module = 'exchanges-store-credit';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-wallet';

    protected static string|\UnitEnum|null $navigationGroup = 'Money';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')->label('Customer'),
                TextColumn::make('currency')->searchable(),
                TextColumn::make('balance_cents')->label('Balance (¢)'),
            ])
            ->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStoreCreditAccounts::route('/'),
            'create' => Pages\CreateStoreCreditAccount::route('/create'),
            'edit' => Pages\EditStoreCreditAccount::route('/{record}/edit'),
        ];
    }
}
