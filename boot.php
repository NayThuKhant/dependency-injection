<?php

$autoLoadDirs = ['./', 'Helpers/'];
foreach ($autoLoadDirs as $dir) {
    $dir = str_ends_with($dir, '/') ? $dir : "$dir/";
    foreach (glob("$dir*.php") as $file) {
        require_once($file);
    }
}



