<?php

use yii\console\controllers\MigrateController;
use yii\i18n\PhpMessageSource;
use yii\redis\Cache;
use yii\redis\Connection;

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
