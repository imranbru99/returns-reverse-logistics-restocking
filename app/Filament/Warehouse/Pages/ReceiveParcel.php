<?php

declare(strict_types=1);

namespace App\Filament\Warehouse\Pages;

use App\Filament\Concerns\HasModuleAccess;
use App\Models\Rma;
use App\Models\Shipment;
use App\Models\Warehouse;
use App\Services\Receiving\ReceiveParcelAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

class ReceiveParcel extends Page implements HasForms
{
    use HasModuleAccess;
    use InteractsWithForms;

    protected static ?string $module = 'receiving-dock';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-qr-code';

    protected static ?string $navigationLabel = 'Receive parcel';

    protected static ?string $title = 'Receive parcel';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.warehouse.pages.receive-parcel';

    public ?string $scan = null;

    public ?string $warehouse_id = null;

    public int $expected_qty = 1;

    public int $received_qty = 1;

    public bool $damaged_in_transit = false;

    public bool $orphan = false;

    public function mount(): void
    {
        $warehouse = Warehouse::query()->where('is_active', true)->first();
        $this->warehouse_id = $warehouse?->getKey();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('scan')
                    ->label('Scan RMA or tracking')
                    ->autofocus()
                    ->required()
                    ->extraInputAttributes(['class' => 'text-2xl font-mono']),
                TextInput::make('expected_qty')->numeric()->default(1),
                TextInput::make('received_qty')->numeric()->default(1),
                Toggle::make('damaged_in_transit'),
                Toggle::make('orphan'),
            ]);
    }

    public function receive(ReceiveParcelAction $action): void
    {
        $data = $this->form->getState();
        $scan = trim((string) ($data['scan'] ?? ''));

        $rma = Rma::query()->where('number', $scan)->first();
        $shipment = Shipment::query()->where('tracking_number', $scan)->first();

        if ($rma === null && $shipment === null) {
            Notification::make()->title('No match found')->danger()->send();

            return;
        }

        $warehouse = Warehouse::query()->find($this->warehouse_id);

        if ($warehouse === null) {
            Notification::make()->title('Select a warehouse')->danger()->send();

            return;
        }

        $user = auth()->user();

        if ($user === null) {
            return;
        }

        $record = $action->execute(
            warehouse: $warehouse,
            receiver: $user,
            rma: $rma ?? $shipment?->rma,
            shipment: $shipment,
            expectedQty: (int) $data['expected_qty'],
            receivedQty: (int) $data['received_qty'],
            damagedInTransit: (bool) $data['damaged_in_transit'],
            orphan: (bool) $data['orphan'],
        );

        Notification::make()
            ->title('Parcel received')
            ->body('Record #'.$record->getKey())
            ->success()
            ->send();

        $this->reset(['scan']);
        $this->form->fill([
            'expected_qty' => 1,
            'received_qty' => 1,
            'damaged_in_transit' => false,
            'orphan' => false,
        ]);
    }

    public static function canAccess(): bool
    {
        if (! static::moduleAccessEnabled()) {
            return false;
        }

        return parent::canAccess();
    }
}
