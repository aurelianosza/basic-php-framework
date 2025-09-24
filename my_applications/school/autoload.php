<?php

spl_autoload_register(function ($class) {
    $baseDirectory = __DIR__ . '/src/';

    $file = $baseDirectory . str_replace('\\', '/', $class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});
