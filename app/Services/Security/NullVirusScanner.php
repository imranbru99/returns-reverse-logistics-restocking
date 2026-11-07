<?php

declare(strict_types=1);

namespace App\Services\Security;

use App\Contracts\ScansUploadedFiles;
use Illuminate\Http\UploadedFile;

final class NullVirusScanner implements ScansUploadedFiles
{
    public function scan(UploadedFile $file): void
    {
        // No-op scanner for local and test environments.
    }
}
