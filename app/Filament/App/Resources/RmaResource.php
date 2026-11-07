<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\RmaResource\Pages;
use App\Filament\Concerns\HasModuleAccess;
use App\Models\Rma;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Textarea;
use App\Enums\RmaStatus;

class RmaResource extends Resource
{
    use HasModuleAccess;

    protected static ?string $model = Rma::class;

    protected static ?string $module = 'rma-management';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-uturn-left';

    protected static string|\UnitEnum|null $navigationGroup = 'Returns';

    protected static ?int $navigationSort = 10;

    public static function getNavigationLabel(): string
    {
        return 'RMAs';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                TextInput::make('number')->disabled()->dehydrated(),
                Select::make('status')->options(collect(RmaStatus::cases())->mapWithKeys(fn (RmaStatus $s) => [$s->value => $s->label()])->all()),
                Select::make('customer_id')->relationship('customer', 'name')->searchable()->preload(),
                Select::make('order_id')->relationship('order', 'number')->searchable()->preload(),
                Textarea::make('reason')->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')->searchable()->sortable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('customer.name')->searchable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRmas::route('/'),
            'create' => Pages\CreateRma::route('/create'),
            'edit' => Pages\EditRma::route('/{record}/edit'),
        ];
    }
}
