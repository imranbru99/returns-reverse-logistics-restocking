<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$lines = [
    '# Modules',
    '',
    'Registry source: `config/modules.php`. Tenants enable modules via `tenant_modules` and the Filament marketplace.',
    '',
];

foreach (config('modules.registry') as $module) {
    $deps = $module['dependencies'] !== [] ? implode(', ', $module['dependencies']) : 'none';

    $lines[] = '## '.$module['name'].' (`'.$module['key'].'`)';
    $lines[] = '';
    $lines[] = '- **Purpose:** '.$module['description'];
    $lines[] = '- **Category:** '.$module['category'];
    $lines[] = '- **Dependencies:** '.$deps;
    $lines[] = '- **Price tier:** '.$module['price_tier'];
    $lines[] = '- **Default enabled:** '.($module['default_enabled'] ? 'yes' : 'no');
    $lines[] = '- **Permissions:** Spatie abilities per tenant team; see [ROLES.md](ROLES.md). Module toggles require `modules.manage` for owners.';
    $lines[] = '- **Settings keys:** `tenant_modules.settings` JSON per module; tenant `settings` / `branding` for org customization.';
    $lines[] = '- **Main routes:** Tenant Filament panel `/app/{tenant}` (resources use `HasModuleAccess`); marketing `/modules`; API uses `module:{key}` middleware when applicable.';
    $lines[] = '';
}

file_put_contents(__DIR__.'/../docs/MODULES.md', implode(PHP_EOL, $lines));

echo "Generated docs/MODULES.md\n";
