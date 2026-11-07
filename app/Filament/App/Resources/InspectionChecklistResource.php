<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\InspectionChecklistResource\Pages;
use App\Filament\Concerns\HasModuleAccess;
use App\Models\InspectionChecklist;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InspectionChecklistResource extends Resource
{
    use HasModuleAccess;

    protected static ?string $model = InspectionChecklist::class;

    protected static ?string $module = 'inspection-engine';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static string|\UnitEnum|null $navigationGroup = 'Inspection';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                TextInput::make('name')->required()->maxLength(255),
                Select::make('category_id')->relationship('category', 'name')->searchable()->preload(),
                Select::make('product_id')->relationship('product', 'name')->searchable()->preload(),
                Repeater::make('steps')
                    ->schema([
                        TextInput::make('key')->required(),
                        TextInput::make('label')->required(),
                        TextInput::make('weight')->numeric()->default(1),
                    ])
                    ->columnSpanFull(),
                TagsInput::make('required_evidence')->columnSpanFull(),
                Toggle::make('is_active')->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('category.name')->label('Category'),
                TextColumn::make('product.name')->label('Product'),
                IconColumn::make('is_active')->boolean(),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInspectionChecklists::route('/'),
            'create' => Pages\CreateInspectionChecklist::route('/create'),
            'edit' => Pages\EditInspectionChecklist::route('/{record}/edit'),
        ];
    }
}
