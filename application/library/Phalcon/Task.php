<?php

namespace MyProject\Phalcon;

use Phalcon\Cli\Dispatcher;
use ReflectionClass;
use ReflectionMethod;
use Phalcon\Cli\Task as PhTask;
use MyProject\Parameters;

/**
 * @property Dispatcher $dispatcher
 * @property Console $console
 * @property Parameters $parameters
 */
class Task extends PhTask
{
    protected static function formatDocComment($doc) {
        $doc = preg_replace('/^[\/\*\s\/]+/m','',$doc);
        return $doc;
    }

    public function initialize() {
        if($this->parameters->hasFlag('help')) {
            $ref = new ReflectionClass(get_called_class());
            $comment = $ref->getMethod($this->dispatcher->getActionName() . 'Action')->getDocComment();
            $comment = self::formatDocComment($comment);
            echo "{$comment}", PHP_EOL;
            exit;
        }
    }

    public function mainAction(Parameters $params)
    {
        $this->console->handle([
            'task'   => $this->dispatcher->getTaskName(),
            'action' => 'help',
        ]);
    }


    public function helpAction()
    {
        $task = $this->dispatcher->getTaskName();
        $class = get_called_class();
        $ref = new ReflectionClass($class);
        foreach ($ref->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $name = $method->getName();
            $refMethod = new ReflectionMethod($class, $name);
            $declClass = $refMethod->getDeclaringClass()->getName();
            if ($name == 'helpAction' || 'Action' !== substr($name, -6, 6) || ($name == 'mainAction' && $declClass == __CLASS__)) {
                continue;
            }
            $name = trim(substr($name, 0, -6));
            $comment = self::formatDocComment($method->getDocComment());
            echo "[{$task}::{$name}]", PHP_EOL;
            echo "{$comment}", PHP_EOL, PHP_EOL;
        }
    }

}
