<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Enums\RefurbishmentWorkOrderStatus;
use App\Filament\App\Resources\RefurbishmentWorkOrderResource\Pages;
use App\Filament\Concerns\HasModuleAccess;
use App\Models\RefurbishmentWorkOrder;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RefurbishmentWorkOrderResource extends Resource
{
    use HasModuleAccess;

    protected static ?string $model = RefurbishmentWorkOrder::class;

    protected static ?string $module = 'refurbishment-repair';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static string|\UnitEnum|null $navigationGroup = 'Refurbishment';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                Select::make('rma_item_id')->relationship('rmaItem', 'id')->searchable(),
                Select::make('status')->options(collect(RefurbishmentWorkOrderStatus::cases())->mapWithKeys(fn ($c) => [$c->value => $c->label()])->all()),
                TextInput::make('parts_cost_cents')->numeric(),
                TextInput::make('labor_minutes')->numeric(),
                Textarea::make('notes')->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rmaItem.rma.number')->label('RMA'),
                TextColumn::make('status')->badge(),
                TextColumn::make('parts_cost_cents')->label('Parts (¢)'),
                TextColumn::make('labor_minutes'),
            ])
            ->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRefurbishmentWorkOrders::route('/'),
            'create' => Pages\CreateRefurbishmentWorkOrder::route('/create'),
            'edit' => Pages\EditRefurbishmentWorkOrder::route('/{record}/edit'),
        ];
    }
}
