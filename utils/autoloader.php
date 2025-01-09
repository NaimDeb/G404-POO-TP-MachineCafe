<?php

spl_autoload_register(function ($className) {
    $baseDir = __DIR__. '/../src/Entities/';
    $file = $baseDir . str_replace('\\', '/', $className) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});