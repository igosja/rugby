<?php

use yii\db\Connection;
use yii\swiftmailer\Mailer;

return [
    'components' => [
        'db' => [
            'class' => Connection::class,
            'dsn' => 'mysql:host=localhost;dbname=yii2advanced',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
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
