<?php

declare(strict_types=1);

namespace App\Services\Warranty;

use App\Enums\WarrantyClaimStatus;
use App\Models\WarrantyClaim;
use App\Models\WarrantyRegistration;
use App\Support\NumberGenerator;
use Illuminate\Support\Facades\DB;

final class FileWarrantyClaimAction
{
    public function __construct(
        private readonly NumberGenerator $numbers,
    ) {}

    public function execute(WarrantyRegistration $registration, string $description): WarrantyClaim
    {
        $tenantId = (string) $registration->tenant_id;

        return DB::transaction(function () use ($registration, $description, $tenantId): WarrantyClaim {
            $number = $this->numbers->next($tenantId, 'warranty_claim', 'WCL');

            return WarrantyClaim::query()->create([
                'tenant_id' => $tenantId,
                'warranty_registration_id' => $registration->getKey(),
                'number' => $number,
                'status' => WarrantyClaimStatus::Submitted,
                'description' => $description,
            ]);
        });
    }
}
