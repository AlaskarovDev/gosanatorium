<?php
$base = dirname(__DIR__);
foreach (['route:clear', 'cache:clear', 'view:clear', 'config:clear'] as $cmd) {
    echo "<b>$cmd:</b> ";
    echo nl2br(htmlspecialchars((string) shell_exec('cd ' . escapeshellarg($base) . " && php artisan $cmd 2>&1")));
    echo "<br>";
}
@unlink(__FILE__);
echo "<br><b>Done. File deleted.</b>";
