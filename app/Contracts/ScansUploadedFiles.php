<?php

declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Http\UploadedFile;

interface ScansUploadedFiles
{
    /**
     * @throws \RuntimeException when malware is detected
     */
    public function scan(UploadedFile $file): void;
}
