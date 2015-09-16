<?php

error_reporting(E_ALL);

if(!defined('APP_PATH'))
    define('APP_PATH', realpath(dirname(__DIR__)).'/application');

use Phalcon\Exception as PhException;
use Phalcon\DI\FactoryDefault as PhInjector;
use Phalcon\Mvc\Router as PhRouter;
use Phalcon\Mvc\Application as PhApplication;

try {

    /**
     * Instantiate default dependency injector
     * @var $di PhInjector
     */
    $di = new PhInjector();
    PhInjector::setDefault($di);

    /**
     * Autoload classes
     */
    require_once APP_PATH.'/loader.php';


    /**
     * Setup routing table
     */
    $di->setShared('router', function() {
        $router = new PhRouter(false);
        $routes = include APP_PATH.'/routes.php';
        foreach($routes as $pattern => $item) {
            if(!$pattern) {
                $router->notFound($item);
            } else {
                $route = $router->add($pattern, $item['handler']);
                if(isset($item['methods'])) $route->via($item['methods']);
                if(isset($item['name'])) $route->setName($item['name']);
            }
        }
        return $router;
    });

    /**
     * Create application instance
     */
    $application = new PhApplication();
    $application->setDI($di);

    /**
     * Register modules from configuration
     */
    $modules = [];
    foreach(include APP_PATH.'/modules.php' as $name => $namespace) {
        $modules[$name] = [
            'className' => $namespace.'\Module',
            'path' => APP_PATH.'/modules/'.$name.'/Module.php',
        ];
    }
    $application->registerModules($modules);

    /**
     * Run application and output result
     */
    echo $application->handle()->getContent();
}

catch(PhException $e) {
    /**
     * Handle framework related exceptions
     */
    echo '<pre>', $e->getMessage(), "\n", $e->getTraceAsString(), '</pre>';
}

catch(PDOException $e) {
    /**
     * Handle database related exceptions
     */
    echo '<pre>', $e->getMessage(), '</pre>';
}

catch(Exception $e) {
    /**
     * Any other exception is unprocessed
     */
    throw $e;
}
