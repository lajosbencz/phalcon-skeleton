<?php

// Uncomment to load Composer dependencies
// require_once __DIR__.'/../vendor/autoload.php';

$loader = new Phalcon\Loader();

$loader->registerNamespaces([
    'MyProject' => APP_PATH.'/library',
]);

$loader->register();
