<?php

namespace MyProject\Console\Task;

use MyProject\Parameters;
use MyProject\Phalcon\Task;

class MainTask extends Task
{
    /**
     * Test documentation
     */
    public function testAction(Parameters $params) {
        var_dump($params->toArray());
    }
}
