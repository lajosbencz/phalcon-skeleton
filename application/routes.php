<?php

return [
    false => [
        'module' => 'frontend',
        'controller' => 'error',
        'action' => 'e404',
    ],
    '/' => [
        'name' => 'home',
        'handler' => [
            'module' => 'frontend',
            'controller' => 'index',
            'action' => 'index',
        ],
    ],
    '/admin' => [
        'name' => 'admin_home',
        'handler' => [
            'module' => 'backend',
            'controller' => 'index',
            'action' => 'index',
        ],
    ],
];

