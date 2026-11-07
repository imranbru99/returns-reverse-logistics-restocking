<?php

declare(strict_types=1);

namespace App\Services\Inspection;

use App\Enums\Grade;
use App\Enums\InspectionStatus;
use App\Enums\RmaStatus;
use App\Models\Inspection;
use App\Models\InspectionChecklist;
use App\Models\User;
use App\Services\Rma\RmaStateMachine;
use App\Support\NumberGenerator;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

final class RunInspectionAction
{
    public function __construct(
        private readonly NumberGenerator $numberGenerator,
        private readonly RmaStateMachine $rmaStateMachine,
    ) {}

    /**
     * @param  array<string, mixed>  $options
     */
    public function start(
        InspectionChecklist $checklist,
        User $inspector,
        ?string $rmaId = null,
        ?string $rmaItemId = null,
        ?string $productId = null,
        array $options = [],
    ): Inspection {
        return DB::transaction(function () use ($checklist, $inspector, $rmaId, $rmaItemId, $productId, $options): Inspection {
            $tenantId = (string) $checklist->tenant_id;

            $inspection = Inspection::query()->create([
                'tenant_id' => $tenantId,
                'rma_id' => $rmaId,
                'rma_item_id' => $rmaItemId,
                'product_id' => $productId ?? $checklist->product_id,
                'inspector_id' => $inspector->getKey(),
                'number' => $this->numberGenerator->next($tenantId, 'inspection', 'INS'),
                'status' => InspectionStatus::InProgress,
                'checklist' => [
                    'checklist_id' => $checklist->getKey(),
                    'name' => $checklist->name,
                    'steps' => $checklist->steps ?? [],
                    'required_evidence' => $checklist->required_evidence ?? [],
                    'responses' => [],
                ],
                'started_at' => now(),
                'meta' => $options,
            ]);

            if ($rmaId !== null) {
                $rma = $inspection->rma;
                if ($rma !== null && $rma->status === RmaStatus::Received) {
                    $this->rmaStateMachine->transition($rma, RmaStatus::Inspecting, $inspector);
                }
            }

            return $inspection->refresh();
        });
    }

    /**
     * @param  array<int, array{key: string, pass: bool, notes?: string}>  $responses
     * @param  array<string, mixed>  $evidence
     */
    public function complete(
        Inspection $inspection,
        array $responses,
        array $evidence = [],
        ?User $actor = null,
    ): Inspection {
        if ($inspection->status !== InspectionStatus::InProgress && $inspection->status !== InspectionStatus::Pending) {
            throw new InvalidArgumentException('Inspection is not in a completable state.');
        }

        return DB::transaction(function () use ($inspection, $responses, $evidence, $actor): Inspection {
            $checklist = $inspection->checklist ?? [];
            $steps = $checklist['steps'] ?? [];
            $score = $this->scoreResponses($steps, $responses);
            $grade = $this->gradeFromScore($score['percent']);

            $checklist['responses'] = $responses;
            $checklist['evidence'] = $evidence;
            $checklist['score'] = $score;

            $inspection->forceFill([
                'status' => InspectionStatus::Completed,
                'grade' => $grade,
                'checklist' => $checklist,
                'completed_at' => now(),
                'meta' => array_merge($inspection->meta ?? [], [
                    'duration_seconds' => $inspection->started_at
                        ? $inspection->started_at->diffInSeconds(now())
                        : null,
                ]),
            ])->save();

            $rma = $inspection->rma;
            if ($rma !== null && $rma->status === RmaStatus::Inspecting) {
                $this->rmaStateMachine->transition($rma, RmaStatus::Inspected, $actor);
            }

            return $inspection->refresh();
        });
    }

    /**
     * @param  array<int, array{key?: string, weight?: int}>  $steps
     * @param  array<int, array{key: string, pass: bool}>  $responses
     *
     * @return array{earned: int, possible: int, percent: float}
     */
    private function scoreResponses(array $steps, array $responses): array
    {
        $responseByKey = collect($responses)->keyBy('key');
        $possible = 0;
        $earned = 0;

        foreach ($steps as $step) {
            $weight = (int) ($step['weight'] ?? 1);
            $key = (string) ($step['key'] ?? '');
            $possible += $weight;

            $response = $responseByKey->get($key);
            if ($response !== null && ($response['pass'] ?? false) === true) {
                $earned += $weight;
            }
        }

        if ($possible === 0) {
            $passed = collect($responses)->where('pass', true)->count();
            $total = max(1, count($responses));

            return [
                'earned' => $passed,
                'possible' => $total,
                'percent' => round(($passed / $total) * 100, 2),
            ];
        }

        return [
            'earned' => $earned,
            'possible' => $possible,
            'percent' => round(($earned / $possible) * 100, 2),
        ];
    }

    private function gradeFromScore(float $percent): Grade
    {
        return match (true) {
            $percent >= 95 => Grade::A,
            $percent >= 80 => Grade::B,
            $percent >= 60 => Grade::C,
            $percent >= 40 => Grade::D,
            default => Grade::Scrap,
        };
    }
}
