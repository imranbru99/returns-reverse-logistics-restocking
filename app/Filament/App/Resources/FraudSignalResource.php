<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\FraudSignalResource\Pages;
use App\Filament\Concerns\HasModuleAccess;
use App\Models\FraudSignal;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FraudSignalResource extends Resource
{
    use HasModuleAccess;

    protected static ?string $model = FraudSignal::class;

    protected static ?string $module = 'fraud-abuse-prevention';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-exclamation';

    protected static string|\UnitEnum|null $navigationGroup = 'Returns';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('score')->searchable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('rma.number')->label('RMA'),
            ])
            ->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFraudSignals::route('/'),
            'create' => Pages\CreateFraudSignal::route('/create'),
            'edit' => Pages\EditFraudSignal::route('/{record}/edit'),
        ];
    }
}
