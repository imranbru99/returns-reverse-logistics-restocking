<?php

declare(strict_types=1);

namespace App\Services\Modules\Exceptions;

use RuntimeException;

final class ModuleHasDependentsException extends RuntimeException
{
    /**
     * @param  array<int, string>  $dependents
     */
    public function __construct(
        public readonly string $module,
        public readonly array $dependents,
    ) {
        parent::__construct(sprintf(
            'Cannot disable module [%s] while dependent modules are enabled: %s',
            $module,
            implode(', ', $dependents),
        ));
    }
}
