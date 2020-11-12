<?php

// TODO refactor

use frontend\components\widgets\LinkPager;
use yii\grid\GridView;
use yii\log\FileTarget;
use yii\redis\Session;
use yii\widgets\ListView;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'container' => [
        'definitions' => [
            GridView::class => [
                'emptyText' => false,
                'options' => ['class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive'],
                'pager' => [
                    'activePageCssClass' => 'btn',
                    'options' => [
                        'class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center margin-top-small',
                        'tag' => 'div',
                    ],
                    'pageCssClass' => 'btn pagination',
                ],
                'tableOptions' => ['class' => 'table table-bordered table-hover'],
            ],
            ListView::class => [
                'emptyText' => false,
                'options' => ['class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12'],
                'pager' => [
                    'activePageCssClass' => 'btn',
                    'options' => [
                        'class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center margin-top-small',
                        'tag' => 'div',
                    ],
                    'pageCssClass' => 'btn pagination',
                ],
            ],
            \yii\widgets\LinkPager::class => [
                'class' => LinkPager::class,
            ],
        ],
    ],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'assetManager' => [
            'appendTimestamp' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
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
            'baseUrl' => '',
            'csrfParam' => '_csrf-frontend',
        ],
        'session' => [
            'class' => Session::class,
            'name' => 'ezubukhqon',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'rules' => [
                '' => 'site/index',
                'activation' => 'site/activation',
                'activation/repeat' => 'site/activation-repeat',
                'forgot-password' => 'site/forgot-password',
                'forum' => 'forum/default/index',
                'password/restore' => 'site/password-restore',
                'sign-in' => 'site/sign-in',
                'sign-out' => 'site/sign-out',
                'sign-up' => 'site/sign-up',
                '<module:(forum)>/<controller:\w+>' => '<module>/<controller>/index',
                '<module:(forum)>/<controller:\w+>/<id:\d+>' => '<module>/<controller>/view',
                '<module:(forum)>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<module>/<controller>/<action>',
                '<module:(forum)>/<controller:\w+>/<action:\w+>/' => '<module>/<controller>/<action>',
                '<controller:\w+>' => '<controller>/index',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>/' => '<controller>/<action>',
            ],
            'showScriptName' => false,
        ],
        'user' => [
            'enableAutoLogin' => true,
            'identityClass' => 'common\models\db\User',
            'identityCookie' => ['name' => 'brynouxwgj', 'httpOnly' => true],
            'loginUrl' => ['site/sign-in'],
        ],
    ],
    'modules' => [
        'forum' => [
            'class' => 'frontend\modules\forum\Module',
        ],
    ],
    'id' => 'app-frontend',
    'params' => $params,
];
