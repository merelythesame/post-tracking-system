<?php
spl_autoload_register(function ($class) {
    $baseDir = __DIR__ . '/';

    $classFile = str_replace('\\', '/', $class) . '.php';

    $fullPath = $baseDir . $classFile;


    if (file_exists($fullPath)) {
        require_once $fullPath;
    }
});
