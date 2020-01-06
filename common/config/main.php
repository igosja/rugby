<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\redis\Cache',
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    //'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                    ],
                ],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SendmailTransport',
            ],
            'viewPath' => '@common/mail',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'database' => 0,
            'hostname' => 'localhost',
            'port' => 6379,
        ],
    ],
    'language' => 'en',
    'timeZone' => 'UTC',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
];
