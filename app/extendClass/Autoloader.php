<?php
define('__URI_ROOT__', __DIR__ . "/../../");

spl_autoload_register(function ($class) {
    $file = __URI_ROOT__ .str_replace('\\', DIRECTORY_SEPARATOR, $class) .'.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
