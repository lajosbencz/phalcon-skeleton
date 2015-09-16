<?php

namespace MyProject\Phalcon;

use Exception;
use Phalcon\Config;
use Phalcon\DiInterface;
use Phalcon\Cli\Dispatcher;
use Phalcon\Cli\Console as PhConsole;
use MyProject\Parameters;
use Phalcon\Loader;


class Console extends PhConsole
{

    public function __construct(DiInterface $di)
    {
        $this->registerAutoloaders();
        $this->registerServices($di);
        parent::__construct($di);
    }

    public function registerAutoloaders() {
        $loader = new Loader();
        $loader->registerNamespaces([
            'MyProject\Console\Task' => CLI_PATH . '/task',
        ]);
        $loader->register();
    }

    public function registerServices(DiInterface $di) {

        $di->setShared('console',$this);

        $di->setShared('dispatcher',function(){
            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace('MyProject\Console\Task');
            $dispatcher->setDefaultTask('main');
            $dispatcher->setDefaultAction('main');
            return $dispatcher;
        });

    }

    public function handle(array $arguments = null)
    {
        $di = $this->getDI();

        if(!isset($arguments['params'])) {
            $arguments['params'] = [];
        }
        if(is_array($arguments['params'])) {
            $arguments['params'] = new Parameters($arguments['params']);
        }

        $di->setShared('parameters',function() use ($arguments) {
            return $arguments['params'];
        });

        try {
            parent::handle($arguments);
        } catch (Exception $e) {
            throw $e;
        }
    }

}