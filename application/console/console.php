<?php

ini_set('memory_limit','-1');
set_time_limit(0);
error_reporting(E_ALL);

if(!defined('APP_PATH'))
    define('APP_PATH', realpath(dirname(__DIR__)));

if(!defined('CLI_PATH'))
    define('CLI_PATH', realpath(__DIR__));

use Phalcon\Di\FactoryDefault\Cli as PhInjector;
use MyProject\Phalcon\Console;

require_once APP_PATH.'/loader.php';

$arguments = [
    'task' => 'main',
    'action' => 'main',
    'params' => [],
];

foreach($argv as $k => $arg) {
    if($k == 1) {
        $arguments['task'] = $arg;
    } elseif($k == 2) {
        $arguments['action'] = $arg;
    } elseif($k >= 3) {
        $arguments['params'][] = $arg;
    }
}

$di = new PhInjector();
PhInjector::setDefault($di);

$console = new Console($di);
$console->handle($arguments);
