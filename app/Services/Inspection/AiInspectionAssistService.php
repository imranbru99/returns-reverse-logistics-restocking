<?php

declare(strict_types=1);

namespace App\Services\Inspection;

use App\Models\Inspection;
use ImranDevBd\AiHub\Facades\AIHub;

final class AiInspectionAssistService
{
    /**
     * @param  array<string, mixed>  $context
     * @return array{
     *     damage_detected: bool,
     *     damage_summary: string,
     *     suggested_grade: string,
     *     confidence: float,
     *     inspection_summary: string,
     *     auto_applied: bool,
     *     raw: array<string, mixed>|null
     * }
     */
    public function analyze(Inspection $inspection, array $context = [], bool $humanOverride = false): array
    {
        $checklist = $inspection->checklist ?? [];
        $prompt = $this->buildPrompt($inspection, $checklist, $context);

        $response = AIHub::prompt($prompt)->generate();
        $parsed = $this->parseResponse($response->content ?? (string) $response);

        $result = [
            'damage_detected' => (bool) ($parsed['damage_detected'] ?? false),
            'damage_summary' => (string) ($parsed['damage_summary'] ?? ''),
            'suggested_grade' => (string) ($parsed['suggested_grade'] ?? 'C'),
            'confidence' => (float) ($parsed['confidence'] ?? 0.0),
            'inspection_summary' => (string) ($parsed['inspection_summary'] ?? ''),
            'auto_applied' => false,
            'raw' => $parsed,
        ];

        if ($humanOverride) {
            $meta = $inspection->meta ?? [];
            $meta['ai_assist'] = $result;
            $meta['ai_assist_applied_at'] = now()->toIso8601String();
            $inspection->forceFill(['meta' => $meta])->save();
            $result['auto_applied'] = true;
        }

        return $result;
    }

    /**
     * @param  array<string, mixed>  $checklist
     * @param  array<string, mixed>  $context
     */
    private function buildPrompt(Inspection $inspection, array $checklist, array $context): string
    {
        $notes = $inspection->notes ?? '';
        $responses = json_encode($checklist['responses'] ?? [], JSON_THROW_ON_ERROR);
        $extra = json_encode($context, JSON_THROW_ON_ERROR);

        return <<<PROMPT
You are a returns warehouse quality assistant. Analyze the inspection context and respond with JSON only:
{"damage_detected":bool,"damage_summary":string,"suggested_grade":"A"|"B"|"C"|"D"|"Scrap","confidence":0-1,"inspection_summary":string}

Inspection number: {$inspection->number}
Notes: {$notes}
Checklist responses: {$responses}
Additional context: {$extra}
PROMPT;
    }

    /**
     * @return array<string, mixed>
     */
    private function parseResponse(string $content): array
    {
        $content = trim($content);

        if (preg_match('/\{.*\}/s', $content, $matches)) {
            $decoded = json_decode($matches[0], true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return [
            'damage_detected' => false,
            'damage_summary' => '',
            'suggested_grade' => 'C',
            'confidence' => 0.5,
            'inspection_summary' => $content,
        ];
    }
}
