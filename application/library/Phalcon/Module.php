<?php

namespace MyProject\Phalcon;

use Phalcon\Config;
use ReflectionClass;
use Phalcon\DiInterface;
use Phalcon\Mvc\Url as PhUrl;
use Phalcon\Mvc\View as PhView;
use Phalcon\Loader as PhLoader;
use Phalcon\Mvc\Dispatcher as PhDispatcher;
use Phalcon\Session\Adapter\Files as PhSession;

abstract class Module
{
    /** @var PhLoader */
    protected $_loader;

    public function __construct() {
        $this->_loader = new PhLoader();
    }

    /**
     * Return namespace => directory dictionary for namespace roots to be loaded
     * <code>
     * return [
     *      'Controllers' => 'controllers',
     * ];
     * </code>
     * @return string[]
     */
    abstract public function getDirs();


    /**
     * Get namespace for module
     * @return string
     */
    public function getNamespace() {
        static $namespace;
        if(!$namespace) {
            $ref = new ReflectionClass($this);
            $namespace = $ref->getNamespaceName();
        }
        return $namespace;
    }

    /**
     * Get filesystem path for module
     * @return string
     */
    public function getPath() {
        static $path;
        if(!$path) {
            $ref = new ReflectionClass($this);
            $path = dirname($ref->getFileName());
        }
        return $path;
    }

    /**
     * Get name of module
     * @return string
     */
    public function getName() {
        static $name;
        if(!$name) {
            $name = basename($this->getPath());
        }
        return $name;
    }

    /**
     * Get config for module, common config is merged
     * @return Config
     */
    public function getConfig() {
        /** @var Config $config */
        static $config;
        if(!$config) {
            $config = include APP_PATH . '/config.php';
            if (is_file($this->getPath() . '/config.php')) {
                $config->merge(include $this->getPath() . '/config.php');
            }
        }
        return $config;
    }

    /**
     * Dependency auto loaders
     * @return void
     */
    public function registerAutoloaders() {
        $namespaces = [];
        foreach($this->getDirs() as $ns => $dir) {
            $namespaces[$this->getNamespace().'\\'.$ns] = $this->getPath().'/'.$dir.'/';
        }
        $this->_loader->registerNamespaces($namespaces, true);
        $this->_loader->register();
    }


    /**
     * Inject services
     * @return void
     */
    public function registerServices(DiInterface $di)
    {
        $module = &$this;
        $config = $this->getConfig();

        $di->setShared('config', function() use($config) {
            return $config;
        });

        $di->setShared('view', function() use($module) {
            $view = new PhView();
            $view->setViewsDir($module->getPath().'/views/');
            $view->setPartialsDir('../../_common/partials/');
            $view->setLayoutsDir('../../_common/layouts/');
            $view->setTemplateAfter('html');
            return $view;
        });


        $di->setShared('dispatcher', function() use($module) {
            $dispatcher = new PhDispatcher();
            $dispatcher->setDefaultNamespace($module->getNamespace().'\Controllers');
            return $dispatcher;
        });


        $di->setShared('url', function() use($config) {
            $url = new PhUrl();
            $url->setBaseUri($config->application->baseUri);
            return $url;
        });


        $di->setShared('session', function() {
            $session = new PhSession();
            $session->start();
            return $session;
        });


        $di->setShared('db', function() use($config) {
            $adapter = $config->db->adapter;
            if(!class_exists($adapter)) {
                throw new \Phalcon\Db\Exception("Invalid adapter");
            }
            /** @var \Phalcon\Db\Adapter\Pdo $db */
            return new $adapter($config->db->options->toArray());
        });

    }

}
