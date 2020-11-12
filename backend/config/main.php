<?php

// TODO refactor

use common\models\db\User;
use yii\grid\GridView;
use yii\log\FileTarget;
use yii\redis\Session;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'assetManager' => [
            'appendTimestamp' => true,
        ],
        'errorHandler' => [
            'errorAction' => '/site/error',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
            'traceLevel' => YII_DEBUG ? 3 : 0,
        ],
        'request' => [
            'baseUrl' => '/qTwnVrXEBN',
            'csrfParam' => '_csrf-backend',
        ],
        'session' => [
            'class' => Session::class,
            'name' => 'pqfteszmme',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'rules' => [
                '' => 'site/index',
                'sign-in' => 'site/sign-in',
                '<controller:\w+>/' => '<controller>/index',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>/' => '<controller>/<action>',
            ],
            'showScriptName' => false,
        ],
        'user' => [
            'enableAutoLogin' => true,
            'identityClass' => User::class,
            'identityCookie' => ['name' => 'miecxkhpeh', 'httpOnly' => true],
            'loginUrl' => ['site/sign-in'],
        ],
    ],
    'container' => [
        'definitions' => [
            GridView::class => [
                'options' => ['class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive'],
                'tableOptions' => ['class' => 'table table-bordered table-hover'],
            ],
        ],
    ],
    'controllerNamespace' => 'backend\controllers',
    'id' => 'app-backend',
    'name' => 'Rugby admin',
    'params' => $params,
];

