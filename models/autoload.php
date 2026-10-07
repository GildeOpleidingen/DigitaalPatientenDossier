<?php
spl_autoload_register(function ($class_naam) {
    $file = __DIR__ . '/' . str_replace('\\', '/', $class_naam) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
