<?php

use backend\assets\AppAsset;
use common\components\helpers\ErrorHelper;
use common\models\queries\SiteQuery;
use common\widgets\Alert;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Breadcrumbs;
use yii\widgets\Menu;

/**
 * @var View $this
 * @var string $content
 */

AppAsset::register($this);

?>
<?php
$this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php
    $this->head() ?>
</head>
<body>
<?php
$this->beginBody() ?>
<div id="wrapper">
    <?php

    NavBar::begin(
        [
            'brandLabel' => 'Админ',
            'brandUrl' => Yii::$app->homeUrl,
            'innerContainerOptions' => ['class' => ''],
            'options' => [
                'class' => 'navbar navbar-default navbar-static-top',
            ],
        ]
    );

    $menuItems = [
        [
            'label' => '<i class="fa fa-bell-o fa-fw"></i> <span class="badge" id="admin-bell" data-url="'
                . Url::to(['bell/index'])
                . '"></span>',
            'items' => [
                [
                    'label' => '<i class="fa fa-user fa-fw"></i> Заявки на команды <span class="badge"></span>',
                    'url' => ['team-request/index'],
                ],
                [
                    'label' => '<i class="fa fa-shield fa-fw"></i> Логотипы <span class="badge admin-logo"></span>',
                    'url' => ['logo/index'],
                ],
                [
                    'label' => '<i class="fa fa-user fa-fw"></i> Фото <span class="badge admin-photo"></span>',
                    'url' => ['photo/index'],
                ],
                [
                    'label' => '<i class="fa fa-comments fa-fw"></i> Тех. поддержка <span class="badge admin-support"></span>',
                    'url' => ['support/index'],
                ],
                [
                    'label' => '<i class="fa fa-exclamation-circle fa-fw"></i> Жалобы <span class="badge admin-complaint"></span>',
                    'url' => ['complaint/index'],
                ],
                [
                    'label' => '<i class="fa fa-bar-chart fa-fw"></i> Опросы <span class="badge admin-poll"></span>',
                    'url' => ['poll/index'],
                ],
            ],
            'url' => 'javascript:',
        ],
        [
            'label' => '<i class="fa fa-gear fa-fw"></i>',
            'items' => [
                [
                    'label' => '<i class="fa fa-power-off fa-fw"></i> ' . (SiteQuery::getStatus(
                        ) ? 'Выключить' : 'Включить'),
                    'url' => ['site/status'],
                ],
                [
                    'label' => '<i class="fa fa-signal fa-fw"></i> Версия сайта',
                    'url' => ['site/version'],
                ],
                [
                    'label' => '<i class="fa fa-file-text-o fa-fw"></i> Логи',
                    'url' => ['logreader'],
                ],
                [
                    'label' => '<i class="fa fa-sign-out fa-fw"></i> Выход',
                    'url' => ['site/logout'],
                ],
            ],
            'url' => 'javascript:',
        ],
    ];

    try {
        print Nav::widget([
            'encodeLabels' => false,
            'items' => $menuItems,
            'options' => ['class' => 'nav navbar-top-links navbar-right'],
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>

    <div class="navbar-default sidebar">
        <div class="sidebar-nav navbar-collapse">
            <?php

            $menuItems = [
                [
                    'label' => 'Пользователи',
                    'template' => '<a href="{url}">{label}<span class="fa arrow"></span></a>',
                    'items' => [
                        [
                            'label' => 'Пользователи',
                            'url' => ['user/index'],
                        ],
                        [
                            'label' => 'Президенты федераций',
                            'url' => ['president/index'],
                        ],
                        [
                            'label' => 'Тренеры сборных',
                            'url' => ['coach/index'],
                        ],
                        [
                            'label' => 'Причины блокировки',
                            'url' => ['block-reason/index'],
                        ],
                    ],
                    'url' => 'javascript:',
                ],
                [
                    'label' => 'Команды',
                    'template' => '<a href="{url}">{label}<span class="fa arrow"></span></a>',
                    'items' => [
                        [
                            'label' => 'Команды',
                            'url' => ['team/index'],
                        ],
                        [
                            'label' => 'Стадионы',
                            'url' => ['stadium/index'],
                        ],
                        [
                            'label' => 'Города',
                            'url' => ['city/index'],
                        ],
                        [
                            'label' => 'Страны',
                            'url' => ['country/index'],
                        ],
                    ],
                    'url' => 'javascript:',
                ],
                [
                    'label' => 'Хоккеисты',
                    'template' => '<a href="{url}">{label}<span class="fa arrow"></span></a>',
                    'items' => [
                        [
                            'label' => 'Составы',
                            'url' => ['squad/index'],
                        ],
                    ],
                    'url' => 'javascript:',
                ],
                [
                    'label' => 'Новости',
                    'template' => '<a href="{url}">{label}<span class="fa arrow"></span></a>',
                    'items' => [
                        [
                            'label' => 'Новости',
                            'url' => ['news/index'],
                        ],
                        [
                            'label' => 'Предварительные новости',
                            'url' => ['pre-news/index'],
                        ],
                    ],
                    'url' => 'javascript:',
                ],
                [
                    'label' => 'Правила',
                    'url' => ['rule/index'],
                ],
                [
                    'label' => 'Опросы',
                    'url' => ['poll/index'],
                ],
                [
                    'label' => 'Расписание',
                    'url' => ['schedule/index'],
                ],
                [
                    'label' => 'Форум',
                    'template' => '<a href="{url}">{label}<span class="fa arrow"></span></a>',
                    'items' => [
                        [
                            'label' => 'Разделы',
                            'url' => ['forum-chapter/index'],
                        ],
                        [
                            'label' => 'Группы',
                            'url' => ['forum-group/index'],
                        ],
                    ],
                    'url' => 'javascript:',
                ],
                [
                    'label' => 'Тексты',
                    'template' => '<a href="{url}">{label}<span class="fa arrow"></span></a>',
                    'items' => [
                        [
                            'label' => 'Типы турниров',
                            'url' => ['tournament-type/index'],
                        ],
                        [
                            'label' => 'Стадии соревнований',
                            'url' => ['stage/index'],
                        ],
                        [
                            'label' => 'Типы турниров',
                            'url' => ['tournament-type/index'],
                        ],
                        [
                            'label' => 'Описания финансовых операций',
                            'url' => ['finance-text/index'],
                        ],
                    ],
                    'url' => 'javascript:',
                ],
                [
                    'label' => 'Показатели сайта',
                    'template' => '<a href="{url}">{label}<span class="fa arrow"></span></a>',
                    'items' => [
                        [
                            'label' => 'Коррекция генератора',
                            'url' => ['analytics/game-statistic'],
                        ],
                        [
                            'label' => 'Слепки состояния сайта',
                            'url' => ['analytics/snapshot'],
                        ],
                    ],
                    'url' => 'javascript:',
                ],
            ];

            try {
                print Menu::widget([
                    'encodeLabels' => false,
                    'items' => $menuItems,
                    'options' => [
                        'id' => 'side-menu',
                        'class' => 'nav',
                    ],
                    'submenuTemplate' => '<ul class="nav nav-second-level">{items}</ul>'
                ]);
            } catch (Exception $e) {
                ErrorHelper::log($e);
            }

            ?>
        </div>
    </div>
    <?php NavBar::end(); ?>
    <div id="page-wrapper">
        <?php

        try {
            print Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]);
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        try {
            print Alert::widget();
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        print $content;

        ?>
    </div>
</div>
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
