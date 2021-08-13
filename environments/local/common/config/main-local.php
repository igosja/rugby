<?php

// TODO refactor

use yii\db\Connection;
use yii\swiftmailer\Mailer;

return [
    'components' => [
        'db' => [
            'class' => Connection::class,
            'charset' => 'utf8',
            'dsn' => 'mysql:host=localhost;dbname=vrol',
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
    ],
];
