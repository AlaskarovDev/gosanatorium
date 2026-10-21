<?php
$base = dirname(dirname(__FILE__));
$cmds = [
    'route:clear',
    'cache:clear',
    'view:clear',
    'config:clear'
];
foreach($cmds as $cmd) {
    echo "<b>$cmd:</b> ";
    echo shell_exec("cd $base && php artisan $cmd 2>&1");
    echo "<br>";
}
unlink(__FILE__);
echo "<br><b>Done. File deleted.</b>";
