<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\WorkTaskResource\Pages;
use App\Filament\Concerns\HasModuleAccess;
use App\Models\WorkTask;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WorkTaskResource extends Resource
{
    use HasModuleAccess;

    protected static ?string $model = WorkTask::class;

    protected static ?string $module = 'tasks-work-queues';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-queue-list';

    protected static string|\UnitEnum|null $navigationGroup = 'People';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                TextInput::make('title')->required()->maxLength(255),
                TextInput::make('priority')->numeric()->default(0),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('queue')->badge(),
                TextColumn::make('title')->searchable(),
                TextColumn::make('priority')->sortable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('sla_due_at')->dateTime()->label('SLA'),
            ])
            ->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkTasks::route('/'),
            'create' => Pages\CreateWorkTask::route('/create'),
            'edit' => Pages\EditWorkTask::route('/{record}/edit'),
        ];
    }
}
