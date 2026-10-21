<?php
// Temporary one-time cache clear. Deletes itself first so it can only run once.
$root = dirname(__DIR__); // Laravel root: the folder containing "artisan"
@unlink(__FILE__);

$output = [];
foreach (['route:clear', 'cache:clear', 'view:clear', 'config:clear'] as $command) {
    $output[] = (string) shell_exec('cd ' . escapeshellarg($root) . ' && php artisan ' . $command . ' 2>&1');
}
foreach ($output as $line) { echo nl2br(htmlspecialchars($line)) . '<br>'; }
echo 'Done. This file has deleted itself.';
