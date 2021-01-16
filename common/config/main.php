<?php

// TODO refactor

use common\components\TranslationEventHandler;
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
                '*' => [
                    'class' => PhpMessageSource::class,
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                        'common' => 'common.php',
                        'console' => 'console.php',
                        'frontend' => 'frontend.php',
                        'rule1' => 'rule1.php',
                        'rule2' => 'rule2.php',
                        'rule3' => 'rule3.php',
                        'rule4' => 'rule4.php',
                        'rule5' => 'rule5.php',
                        'rule6' => 'rule6.php',
                        'rule7' => 'rule7.php',
                        'rule8' => 'rule8.php',
                        'rule9' => 'rule9.php',
                        'rule10' => 'rule10.php',
                        'rule11' => 'rule11.php',
                        'rule12' => 'rule12.php',
                        'rule13' => 'rule13.php',
                        'rule14' => 'rule14.php',
                        'rule15' => 'rule15.php',
                        'rule16' => 'rule16.php',
                        'rule17' => 'rule17.php',
                        'rule18' => 'rule18.php',
                        'rule19' => 'rule19.php',
                        'rule20' => 'rule20.php',
                        'rule21' => 'rule21.php',
                        'rule22' => 'rule22.php',
                        'rule23' => 'rule23.php',
                        'rule24' => 'rule24.php',
                        'rule25' => 'rule25.php',
                        'rule26' => 'rule26.php',
                        'rule27' => 'rule27.php',
                        'rule28' => 'rule28.php',
                        'rule29' => 'rule29.php',
                        'rule30' => 'rule30.php',
                    ],
                    'on missingTranslation' => [TranslationEventHandler::class, 'handleMissingTranslation'],
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
