<?php

namespace MyProject\Frontend;

use MyProject\Phalcon\Module as MyModule;

class Module extends MyModule
{

    public function getDirs() {
        return [
            'Controllers' => 'controllers',
        ];
    }

}
