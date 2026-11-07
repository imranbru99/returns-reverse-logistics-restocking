<?php

declare(strict_types=1);

namespace App\Filament\Warehouse\Pages;

use App\Filament\Concerns\HasModuleAccess;
use App\Models\Inspection;
use App\Models\InspectionChecklist;
use App\Services\Inspection\AiInspectionAssistService;
use App\Services\Inspection\RunInspectionAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

class RunInspection extends Page implements HasForms
{
    use HasModuleAccess;
    use InteractsWithForms;

    protected static ?string $module = 'inspection-engine';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-magnifying-glass-circle';

    protected static ?string $navigationLabel = 'Run inspection';

    protected static ?string $title = 'Run inspection';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.warehouse.pages.run-inspection';

    public ?string $rma_number = null;

    public ?string $checklist_id = null;

    public ?string $inspection_id = null;

    public ?string $ai_summary = null;

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('rma_number')->label('RMA number')->required(),
            Select::make('checklist_id')
                ->label('Checklist')
                ->options(fn (): array => InspectionChecklist::query()->where('is_active', true)->pluck('name', 'id')->all())
                ->required(),
        ]);
    }

    public function start(RunInspectionAction $action): void
    {
        $data = $this->form->getState();
        $checklist = InspectionChecklist::query()->find($data['checklist_id']);
        $user = auth()->user();

        if ($checklist === null || $user === null) {
            return;
        }

        $rma = \App\Models\Rma::query()->where('number', $data['rma_number'])->first();

        $inspection = $action->start(
            checklist: $checklist,
            inspector: $user,
            rmaId: $rma?->getKey(),
        );

        $this->inspection_id = (string) $inspection->getKey();

        Notification::make()->title('Inspection started')->body($inspection->number)->success()->send();
    }

    public function complete(RunInspectionAction $action): void
    {
        $inspection = Inspection::query()->find($this->inspection_id);

        if ($inspection === null) {
            Notification::make()->title('Start an inspection first')->warning()->send();

            return;
        }

        $steps = $inspection->checklist['steps'] ?? [];
        $responses = collect($steps)->map(fn (array $step): array => [
            'key' => (string) ($step['key'] ?? ''),
            'pass' => (bool) ($step['pass'] ?? true),
        ])->all();

        $completed = $action->complete($inspection, $responses, [], auth()->user());

        Notification::make()
            ->title('Inspection completed')
            ->body('Grade: '.($completed->grade?->value ?? '—'))
            ->success()
            ->send();
    }

    public function assist(AiInspectionAssistService $assist): void
    {
        $inspection = Inspection::query()->find($this->inspection_id);

        if ($inspection === null) {
            Notification::make()->title('Start an inspection first')->warning()->send();

            return;
        }

        $result = $assist->analyze($inspection, [], humanOverride: false);
        $this->ai_summary = $result['inspection_summary'];

        Notification::make()
            ->title('AI assist ready')
            ->body('Suggested grade '.$result['suggested_grade'].' ('.round($result['confidence'] * 100).'% confidence)')
            ->success()
            ->send();
    }

    public static function canAccess(): bool
    {
        if (! static::moduleAccessEnabled()) {
            return false;
        }

        return parent::canAccess();
    }
}
