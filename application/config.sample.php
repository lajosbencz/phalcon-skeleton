<?php

return new Phalcon\Config([

    'application' => [
        'title' => 'My Project \o/',
        'baseUri' => '/',
        'logDir' => __DIR__.'/../log',
        'tempDir' => __DIR__.'/../tmp',
        'cacheDir' => __DIR__.'/../cache',
    ],

    'db' => [
        'adapter' => 'Phalcon\Db\Adapter\Pdo\Mysql',
        'dialect' => '',
        'options' => [
            'host' => 'localhost',
            'username' => 'user',
            'password' => 'secret',
            'dbname' => 'database',
            'charset' => 'utf8',
        ],
    ],

]);
