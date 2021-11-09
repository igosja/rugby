<?php

// TODO refactor

use yii\db\Connection as DbConnection;
use yii\redis\Connection as RedisConnection;
use yii\swiftmailer\Mailer;

return [
    'components' => [
        'db' => [
            'class' => DbConnection::class,
            'charset' => 'utf8',
            'dsn' => 'mysql:host=localhost;dbname=admin_vrol',
            'enableSchemaCache' => true,
            'password' => 'JGvjBoCmVhLVzKDnYiH6',
            'schemaCacheDuration' => 3600,
            'username' => 'admin_vrol',
        ],
        'mailer' => [
            'class' => Mailer::class,
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'encryption' => 'ssl',
                'host' => 'smtp-pulse.com',
                'password' => 'W93pcY9MW7L',
                'port' => '465',
                'username' => 'igosja@ukr.net',
            ],
        ],
        'redis' => [
            'class' => RedisConnection::class,
            'database' => 0,
            'hostname' => 'localhost',
            'port' => 6379,
        ],
    ],
];
