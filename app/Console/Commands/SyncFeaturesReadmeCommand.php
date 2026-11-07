<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

final class SyncFeaturesReadmeCommand extends Command
{
    protected $signature = 'features:sync-readme';

    protected $description = 'Regenerate the FEATURES section in README.md from config/features.php';

    public function handle(): int
    {
        $readmePath = base_path('README.md');
        $contents = (string) file_get_contents($readmePath);

        $startMarker = '<!-- FEATURES:START -->';
        $endMarker = '<!-- FEATURES:END -->';

        if (! str_contains($contents, $startMarker) || ! str_contains($contents, $endMarker)) {
            $this->error('README.md is missing FEATURES markers.');

            return self::FAILURE;
        }

        $section = $this->buildFeaturesSection();

        $pattern = '/'.preg_quote($startMarker, '/').'.*?'.preg_quote($endMarker, '/').'/s';
        $replacement = $startMarker."\n".$section."\n".$endMarker;

        $updated = (string) preg_replace($pattern, $replacement, $contents, 1);
        file_put_contents($readmePath, $updated);

        $this->info('README FEATURES section updated.');

        return self::SUCCESS;
    }

    private function buildFeaturesSection(): string
    {
        $features = config('features.features', []);
        $lines = ['## Features', ''];

        foreach ($features as $feature) {
            $name = (string) ($feature['name'] ?? $feature['key'] ?? 'Feature');
            $description = (string) ($feature['description'] ?? '');
            $module = (string) ($feature['module'] ?? $feature['key'] ?? '');
            $lines[] = sprintf('- **%s** (`%s`) — %s', $name, $module, $description);
        }

        $lines[] = '';

        return implode("\n", $lines);
    }
}
