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
            'dsn' => 'mysql:host=dbvrol;dbname=vrol',
            'enableSchemaCache' => true,
            'password' => 'JGvjBoCmVhLVzKDnYiH6',
            'schemaCacheDuration' => 3600,
            'username' => 'vrol',
        ],
        'mailer' => [
            'class' => Mailer::class,
            'viewPath' => '@common/mail',
            'useFileTransport' => true,
        ],
        'redis' => [
            'class' => RedisConnection::class,
            'database' => 0,
            'hostname' => 'redis',
            'port' => 6379,
        ],
    ],
];
