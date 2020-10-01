<?php

use yii\swiftmailer\Mailer;
use yii\db\Connection;

return [
    'components' => [
        'db' => [
            'class' => Connection::class,
            'charset' => 'utf8',
            'dsn' => 'mysql:host=localhost;dbname=vrol',
            'enableSchemaCache' => true,
            'password' => 'JGvjBoCmVhLVzKDnYiH6',
            'schemaCacheDuration' => 60,
            'username' => 'vrol',
        ],
        'mailer' => [
            'class' => Mailer::class,
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];
