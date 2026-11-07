<?php

declare(strict_types=1);

namespace App\Integrations;

use App\Contracts\CommerceChannelInterface;
use App\Enums\IntegrationDriver;
use App\Models\IntegrationConnection;
use Illuminate\Support\Facades\Storage;

final class CsvImportAdapter implements CommerceChannelInterface
{
    public function driver(): string
    {
        return IntegrationDriver::Csv->value;
    }

    public function fetchOrders(IntegrationConnection $connection, ?\DateTimeInterface $since = null): array
    {
        $path = data_get($connection->settings, 'csv_path');

        if (! is_string($path) || ! Storage::disk('local')->exists($path)) {
            return [];
        }

        $handle = fopen(Storage::disk('local')->path($path), 'rb');

        if ($handle === false) {
            return [];
        }

        $headers = fgetcsv($handle);
        $orders = [];

        if (! is_array($headers)) {
            fclose($handle);

            return [];
        }

        while (($row = fgetcsv($handle)) !== false) {
            if (! is_array($row)) {
                continue;
            }

            $record = array_combine($headers, $row);

            if ($record === false) {
                continue;
            }

            $orders[] = [
                'external_id' => (string) ($record['external_id'] ?? $record['order_number'] ?? ''),
                'number' => (string) ($record['order_number'] ?? $record['number'] ?? ''),
                'customer_email' => (string) ($record['customer_email'] ?? ''),
                'total' => (float) ($record['total'] ?? 0),
                'placed_at' => (string) ($record['placed_at'] ?? now()->toIso8601String()),
            ];
        }

        fclose($handle);

        return $orders;
    }

    public function testConnection(IntegrationConnection $connection, array $payload = []): bool
    {
        $path = data_get($connection->settings, 'csv_path');

        return is_string($path) && Storage::disk('local')->exists($path);
    }
}
