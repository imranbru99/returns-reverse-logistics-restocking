<?php

declare(strict_types=1);

namespace App\Enums;

enum IntegrationConnectionStatus: string
{
    case Connected = 'connected';
    case Disconnected = 'disconnected';
    case Error = 'error';
    case Syncing = 'syncing';

    public function label(): string
    {
        return match ($this) {
            self::Connected => 'Connected',
            self::Disconnected => 'Disconnected',
            self::Error => 'Error',
            self::Syncing => 'Syncing',
        };
    }
}
