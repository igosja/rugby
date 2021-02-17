<?php

// TODO refactor

use yii\console\controllers\FixtureController;
use yii\web\UrlManager;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'console\controllers',
    'controllerMap' => [
        'fixture' => [
            'class' => FixtureController::class,
            'namespace' => 'common\fixtures',
        ],
    ],
    'components' => [
        'urlManager' => [
            'baseUrl' => '/',
            'class' => UrlManager::class,
            'enablePrettyUrl' => true,
            'scriptUrl' => 'index.php',
            'showScriptName' => false,
        ]
    ],
    'id' => 'app-console',
    'params' => $params,
];
