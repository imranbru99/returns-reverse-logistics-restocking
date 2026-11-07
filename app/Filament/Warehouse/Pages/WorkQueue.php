<?php

declare(strict_types=1);

namespace App\Filament\Warehouse\Pages;

use App\Enums\WorkTaskQueue;
use App\Enums\WorkTaskStatus;
use App\Filament\Concerns\HasModuleAccess;
use App\Models\WorkTask;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class WorkQueue extends Page implements HasTable
{
    use HasModuleAccess;
    use InteractsWithTable;

    protected static ?string $module = 'tasks-work-queues';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $navigationLabel = 'Work queue';

    protected static ?string $title = 'Operational work queue';

    protected static ?int $navigationSort = 5;

    protected string $view = 'filament.warehouse.pages.work-queue';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                WorkTask::query()->whereIn('status', [
                    WorkTaskStatus::Pending,
                    WorkTaskStatus::InProgress,
                ])
            )
            ->defaultSort('priority')
            ->columns([
                TextColumn::make('queue')->badge(),
                TextColumn::make('title')->searchable(),
                TextColumn::make('priority')->sortable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('sla_due_at')->dateTime()->label('SLA due'),
                TextColumn::make('assignee.name')->label('Assignee'),
            ])
            ->filters([
                SelectFilter::make('queue')
                    ->options(collect(WorkTaskQueue::cases())->mapWithKeys(fn ($c) => [$c->value => $c->label()])->all()),
            ]);
    }
}
