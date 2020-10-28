<?php

use yii\console\controllers\MigrateController;
use yii\i18n\PhpMessageSource;
use yii\redis\Cache;
use yii\redis\Connection;
use yii\swiftmailer\Mailer;

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'cache' => [
            'class' => Cache::class,
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => PhpMessageSource::class,
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                    ],
                ],
            ],
        ],
        'mailer' => [
            'class' => Mailer::class,
            'transport' => [
                'class' => 'Swift_SendmailTransport',
                'encryption' => 'ssl',
                'host' => 'smtp-pulse.com',
                'password' => 'W93pcY9MW7L',
                'port' => '465',
                'username' => 'igosja@ukr.net',
            ],
            'useFileTransport' => false,
            'viewPath' => '@common/mail',
        ],
        'redis' => [
            'class' => Connection::class,
            'database' => 0,
            'hostname' => 'localhost',
            'port' => 6379,
        ],
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => MigrateController::class,
            'migrationPath' => null,
            'migrationNamespaces' => [
                'console\migrations',
            ],
        ],
    ],
    'language' => 'en',
    'timeZone' => 'UTC',
    'vendorPath' => dirname(__DIR__, 2) . '/vendor',
];
