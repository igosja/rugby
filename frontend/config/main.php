<?php

// TODO refactor

use common\models\db\User;
use frontend\components\FrontendRequest;
use frontend\components\widgets\LinkPager as FrontendLinkPager;
use frontend\modules\federation\Module as ModuleFederation;
use frontend\modules\forum\Module as ModuleForum;
use kartik\select2\Select2;
use yii\grid\GridView;
use yii\redis\Session;
use yii\web\Request;
use yii\widgets\LinkPager as BaseLinkPager;
use yii\widgets\ListView;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'basePath' => dirname(__DIR__),
    'container' => [
        'definitions' => [
            Request::class => FrontendRequest::class,
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
            BaseLinkPager::class => [
                'class' => FrontendLinkPager::class,
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
            Select2::class => [
                'theme' => Select2::THEME_DEFAULT,
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
                'sitemap.xml' => 'sitemap/index',
                '<module:(federation)>/<controller:\w+>/<id:\d+>' => '<module>/<controller>/index',
                '<module:(forum)>/<controller:\w+>/<id:\d+>' => '<module>/<controller>/view',
                '<module:(federation|forum)>/<controller:\w+>' => '<module>/<controller>/index',
                '<module:(federation|forum)>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<module>/<controller>/<action>',
                '<module:(federation|forum)>/<controller:\w+>/<action:\w+>/' => '<module>/<controller>/<action>',
                '<controller:\w+>' => '<controller>/index',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>/' => '<controller>/<action>',
            ],
            'showScriptName' => false,
        ],
        'user' => [
            'enableAutoLogin' => true,
            'identityClass' => User::class,
            'identityCookie' => ['name' => 'brynouxwgj', 'httpOnly' => true],
            'loginUrl' => ['site/sign-in'],
        ],
    ],
    'modules' => [
        'federation' => [
            'class' => ModuleFederation::class,
        ],
        'forum' => [
            'class' => ModuleForum::class,
        ],
    ],
    'id' => 'app-frontend',
    'params' => $params,
];
