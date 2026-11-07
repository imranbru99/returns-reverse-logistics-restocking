<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\InspectionResource\Pages;
use App\Filament\Concerns\HasModuleAccess;
use App\Models\Inspection;
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
use App\Enums\InspectionStatus;

class InspectionResource extends Resource
{
    use HasModuleAccess;

    protected static ?string $model = Inspection::class;

    protected static ?string $module = 'inspection-engine';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-magnifying-glass-circle';

    protected static string|\UnitEnum|null $navigationGroup = 'Inspection';

    protected static ?int $navigationSort = 10;

    public static function getNavigationLabel(): string
    {
        return 'Inspections';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                TextInput::make('number')->required()->maxLength(255),
                Select::make('status')->options(collect(InspectionStatus::cases())->mapWithKeys(fn (InspectionStatus $s) => [$s->value => $s->label()])->all())->required(),
                Select::make('rma_id')->relationship('rma', 'number')->searchable()->preload(),
                Textarea::make('notes')->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')->searchable()->sortable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('rma.number')->label('RMA'),
                TextColumn::make('grade'),
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
            'index' => Pages\ListInspections::route('/'),
            'create' => Pages\CreateInspection::route('/create'),
            'edit' => Pages\EditInspection::route('/{record}/edit'),
        ];
    }
}
