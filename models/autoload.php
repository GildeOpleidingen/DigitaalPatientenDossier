<?php
spl_autoload_register(function ($class_naam) {
    $sanitized = str_replace('\\', '/', $class_naam);

    $modelFile = __DIR__ . '/' . $sanitized . '.php';
    if (file_exists($modelFile)) {
        require_once $modelFile;
        return;
    }

    $dbFile = __DIR__ . '/../database/' . $sanitized . '.php';
    if (file_exists($dbFile)) {
        require_once $dbFile;
        return;
    }
});
