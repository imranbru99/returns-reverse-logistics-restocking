<?php

declare(strict_types=1);

namespace App\Services\Revenue;

use App\Models\DestructionCertificate;
use App\Models\RecyclingPartner;
use App\Support\NumberGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ImranDev\UnicodePdf\Facades\UnicodePdf;

final class IssueDestructionCertificateAction
{
    public function __construct(
        private readonly NumberGenerator $numbers,
    ) {}

    /**
     * @param  array<int, array<string, mixed>>  $items
     */
    public function execute(?RecyclingPartner $partner, array $items): DestructionCertificate
    {
        $tenantId = (string) ($partner?->tenant_id ?? tenant_id());

        if ($tenantId === '') {
            throw new \InvalidArgumentException('Tenant context is required.');
        }

        return DB::transaction(function () use ($partner, $items, $tenantId): DestructionCertificate {
            $number = $this->numbers->next($tenantId, 'destruction_cert', 'DSC');

            $certificate = DestructionCertificate::query()->create([
                'tenant_id' => $tenantId,
                'recycling_partner_id' => $partner?->getKey(),
                'certificate_number' => $number,
                'issued_at' => now(),
                'items' => $items,
            ]);

            $relativePath = "certificates/{$tenantId}/{$certificate->certificate_number}.pdf";
            $absolutePath = Storage::disk('local')->path($relativePath);

            if (! is_dir(dirname($absolutePath))) {
                mkdir(dirname($absolutePath), 0755, true);
            }

            UnicodePdf::view('pdf.destruction-certificate', [
                'certificate' => $certificate,
                'partner' => $partner,
                'items' => $items,
            ])
                ->paper('a4')
                ->save($absolutePath);

            $certificate->forceFill(['pdf_path' => $relativePath])->save();

            return $certificate->refresh();
        });
    }
}
