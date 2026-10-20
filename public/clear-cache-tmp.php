<?php
// Temporary one-time cache clear. Deletes itself first, so it can only run once.
@unlink(__FILE__);

define('LARAVEL_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

header('Content-Type: text/plain; charset=utf-8');
foreach (['cache:clear', 'view:clear', 'config:clear', 'optimize:clear'] as $command) {
    try {
        $code = $kernel->call($command);
        echo $command.': '.($code === 0 ? 'ok' : 'failed')."\n";
    } catch (Throwable $e) {
        echo $command.": failed\n";
    }
}
echo "DONE\n";
