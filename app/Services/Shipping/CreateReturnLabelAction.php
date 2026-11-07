<?php

declare(strict_types=1);

namespace App\Services\Shipping;

use App\Enums\RmaStatus;
use App\Enums\ShipmentDirection;
use App\Enums\ShipmentStatus;
use App\Models\CarrierAccount;
use App\Models\Rma;
use App\Models\Shipment;
use App\Models\Tenant;
use App\Services\Rma\RmaStateMachine;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ImranDev\UnicodePdf\Facades\UnicodePdf;

final class CreateReturnLabelAction
{
    public function __construct(
        private readonly CarrierManager $carrierManager,
        private readonly RmaStateMachine $rmaStateMachine,
    ) {}

    /**
     * @param  array<string, mixed>  $parcel
     */
    public function execute(Rma $rma, ?CarrierAccount $account = null, array $parcel = []): Shipment
    {
        return DB::transaction(function () use ($rma, $account, $parcel): Shipment {
            $account ??= $this->carrierManager->defaultForTenant((string) $rma->tenant_id);

            if ($account === null) {
                throw new \RuntimeException('No carrier account configured for this tenant.');
            }

            $adapter = $this->carrierManager->resolve($account);
            $rate = $adapter->rate($account, $rma, $parcel);
            $label = $adapter->createLabel($account, $rma, $parcel);

            $shipment = Shipment::query()->create([
                'tenant_id' => $rma->tenant_id,
                'rma_id' => $rma->getKey(),
                'direction' => ShipmentDirection::Inbound,
                'carrier' => $account->driver->value,
                'tracking_number' => $label['tracking_number'],
                'status' => ShipmentStatus::LabelCreated,
                'rate_cents' => $rate['rate_cents'],
                'meta' => array_merge($label['meta'] ?? [], ['service' => $rate['service']]),
                'shipped_at' => now(),
            ]);

            $sandbox = config('returns.demo_mode') || config('returns.sandbox.carriers') || $account->sandbox;

            if ($sandbox) {
                Log::info('Return label created in sandbox/demo mode', [
                    'rma' => $rma->number,
                    'tracking' => $shipment->tracking_number,
                    'tenant_id' => $rma->tenant_id,
                ]);
                $shipment->forceFill(['label_path' => 'labels/demo/'.$shipment->tracking_number.'.pdf'])->save();
            } else {
                $path = $this->storeLabelPdf($rma, $shipment);
                $shipment->forceFill(['label_path' => $path])->save();
            }

            if ($rma->status === RmaStatus::Approved) {
                $this->rmaStateMachine->transition($rma, RmaStatus::LabelIssued);
            }

            return $shipment->refresh();
        });
    }

    private function storeLabelPdf(Rma $rma, Shipment $shipment): string
    {
        $tenant = Tenant::query()->find($rma->tenant_id);
        $branding = is_array($tenant?->branding) ? $tenant->branding : [];

        $relative = 'labels/'.$rma->tenant_id.'/'.$shipment->tracking_number.'.pdf';
        $absolute = Storage::disk('local')->path($relative);

        if (! is_dir(dirname($absolute))) {
            mkdir(dirname($absolute), 0755, true);
        }

        UnicodePdf::view('pdf.return-label', [
            'rma' => $rma,
            'shipment' => $shipment,
            'tenantName' => $tenant?->name ?? config('returns.brand.name'),
            'brandColor' => $branding['primary_color'] ?? '#047857',
        ])->save($absolute);

        return $relative;
    }
}
