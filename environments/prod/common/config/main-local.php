<?php

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
    ],
];
