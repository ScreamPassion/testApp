<?php

//require_once 'vendor/autoload.php';
require_once 'conf.php';

spl_autoload_register(function ($class) {
    if (strpos($class, '\\') !== false) {
        $class = mb_substr($class, strpos($class, '\\') + 1);
    }

    if (file_exists(__DIR__.'/libs/'.$class.'.php')) {
        require_once __DIR__.'/libs/'.$class.'.php';
    }
});
