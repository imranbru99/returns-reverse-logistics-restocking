<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Enums\PutawayTaskStatus;
use App\Filament\App\Resources\PutawayTaskResource\Pages;
use App\Filament\Concerns\HasModuleAccess;
use App\Models\PutawayTask;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PutawayTaskResource extends Resource
{
    use HasModuleAccess;

    protected static ?string $model = PutawayTask::class;

    protected static ?string $module = 'restocking-putaway';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-archive-box-arrow-down';

    protected static string|\UnitEnum|null $navigationGroup = 'Restocking';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                Select::make('inventory_item_id')->relationship('inventoryItem', 'id')->searchable(),
                Select::make('to_bin_id')->relationship('toBin', 'code')->required(),
                Select::make('status')->options(collect(PutawayTaskStatus::cases())->mapWithKeys(fn ($c) => [$c->value => $c->label()])->all()),
                Select::make('assigned_to')->relationship('assignee', 'name'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('inventoryItem.product.name')->label('Product'),
                TextColumn::make('toBin.code')->label('To bin'),
                TextColumn::make('status')->badge(),
                TextColumn::make('assignee.name')->label('Assigned'),
            ])
            ->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPutawayTasks::route('/'),
            'create' => Pages\CreatePutawayTask::route('/create'),
            'edit' => Pages\EditPutawayTask::route('/{record}/edit'),
        ];
    }
}
