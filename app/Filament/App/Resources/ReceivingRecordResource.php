<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ReceivingRecordResource\Pages;
use App\Filament\Concerns\HasModuleAccess;
use App\Models\ReceivingRecord;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReceivingRecordResource extends Resource
{
    use HasModuleAccess;

    protected static ?string $model = ReceivingRecord::class;

    protected static ?string $module = 'receiving-dock';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-inbox-arrow-down';

    protected static string|\UnitEnum|null $navigationGroup = 'Warehouse';

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                Select::make('warehouse_id')->relationship('warehouse', 'name')->required(),
                Select::make('rma_id')->relationship('rma', 'number')->searchable(),
                TextInput::make('expected_qty')->numeric()->default(1),
                TextInput::make('received_qty')->numeric()->default(1),
                Toggle::make('damaged_in_transit'),
                Toggle::make('orphan'),
                Textarea::make('notes')->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rma.number')->label('RMA'),
                TextColumn::make('warehouse.name'),
                TextColumn::make('received_qty'),
                TextColumn::make('received_at')->dateTime(),
                IconColumn::make('orphan')->boolean(),
            ])
            ->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReceivingRecords::route('/'),
            'create' => Pages\CreateReceivingRecord::route('/create'),
            'edit' => Pages\EditReceivingRecord::route('/{record}/edit'),
        ];
    }
}
