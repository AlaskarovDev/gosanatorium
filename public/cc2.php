<?php
$base=dirname(dirname(__FILE__));
$cmds=['route:clear','cache:clear','view:clear','config:clear','optimize:clear'];
foreach($cmds as $cmd){
    echo "<b>$cmd:</b> ";
    echo shell_exec("cd $base && php artisan $cmd 2>&1");
    echo "<br>";
}
$viewCache = $base.'/storage/framework/views';
if(is_dir($viewCache)){
    $files = glob($viewCache.'/*');
    $count = 0;
    foreach($files as $f){
        if(is_file($f)){ unlink($f); $count++; }
    }
    echo "<br><b>Deleted $count compiled view files</b><br>";
}
unlink(__FILE__);
echo "<br><b>Done.</b>";
