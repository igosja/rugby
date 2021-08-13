<?php

// TODO refactor

use backend\assets\AppAsset;
use common\components\helpers\ErrorHelper;
use common\models\queries\SiteQuery;
use common\widgets\Alert;
use rmrevin\yii\fontawesome\FAS;
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
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?= Yii::$app->request->baseUrl ?>/favicon.ico"/>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div id="wrapper">
    <?php

    NavBar::begin(
        [
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'innerContainerOptions' => ['class' => ''],
            'options' => [
                'class' => 'navbar navbar-default navbar-static-top',
            ],
        ]
    );

    $menuItems = [
        [
            'label' => FAS::icon(FAS::_BELL)
                . ' <span class="badge" id="admin-bell" data-url="'
                . Url::to(['bell/index'])
                . '"></span>',
            'items' => [
                [
                    'label' => FAS::icon(FAS::_FOOTBALL_BALL) . ' ' . Yii::t('backend', 'views.layout.main.label.team-request') . ' <span class="badge"></span>',
                    'url' => ['team-request/index'],
                ],
                [
                    'label' => FAS::icon(FAS::_SHIELD_ALT) . ' ' . Yii::t('backend', 'views.layout.main.label.logo') . ' <span class="badge admin-logo"></span>',
                    'url' => ['logo/index'],
                ],
                [
                    'label' => FAS::icon(FAS::_USER) . ' ' . Yii::t('backend', 'views.layout.main.label.photo') . ' <span class="badge admin-photo"></span>',
                    'url' => ['photo/index'],
                ],
                [
                    'label' => FAS::icon(FAS::_COMMENTS) . ' ' . Yii::t('backend', 'views.layout.main.label.support') . ' <span class="badge admin-support"></span>',
                    'url' => ['support/index'],
                ],
                [
                    'label' => FAS::icon(FAS::_EXCLAMATION_CIRCLE) . ' ' . Yii::t('backend', 'views.layout.main.label.complaint') . ' <span class="badge admin-complaint"></span>',
                    'url' => ['complaint/index'],
                ],
                [
                    'label' => FAS::icon(FAS::_CHART_BAR) . ' ' . Yii::t('backend', 'views.layout.main.label.vote') . ' <span class="badge admin-vote"></span>',
                    'url' => ['vote/index'],
                ],
            ],
            'url' => 'javascript:',
        ],
        [
            'label' => FAS::icon(FAS::_FILE_ALT),
            'items' => [
                [
                    'label' => Yii::t('backend', 'views.layout.main.label.log'),
                    'url' => ['log/index'],
                ],
            ],
            'url' => 'javascript:',
        ],
        [
            'label' => FAS::icon(FAS::_COG),
            'items' => [
                [
                    'label' => FAS::icon(FAS::_POWER_OFF) . ' ' . (SiteQuery::getStatus() ? Yii::t('backend', 'views.layout.main.label.turn-off') : Yii::t('backend', 'views.layout.main.label.turn-on')),
                    'url' => ['site/status'],
                ],
                [
                    'label' => FAS::icon(FAS::_SIGNAL) . ' ' . Yii::t('backend', 'views.layout.main.label.version'),
                    'url' => ['site/version'],
                ],
                [
                    'label' => FAS::icon(FAS::_SIGN_OUT_ALT) . ' ' . Yii::t('backend', 'views.layout.main.label.logout'),
                    'url' => ['site/logout'],
                ],
            ],
            'url' => 'javascript:',
        ],
    ];

    try {
        print Nav::widget(
            [
                'encodeLabels' => false,
                'items' => $menuItems,
                'options' => ['class' => 'nav navbar-top-links navbar-right'],
            ]
        );
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }
    ?>

    <?php NavBar::end() ?>
    <div class="navbar-default sidebar">
        <div class="sidebar-nav navbar-collapse">
            <?php

            $menuItems = [
                [
                    'label' => Yii::t('backend', 'views.layout.main.label.user'),
                    'template' => '<a href="{url}">{label}<span class="fa arrow"></span></a>',
                    'items' => [
                        [
                            'label' => Yii::t('backend', 'views.layout.main.label.user'),
                            'url' => ['user/index'],
                        ],
                    ],
                    'url' => 'javascript:',
                ],
                [
                    'label' => Yii::t('backend', 'views.layout.main.label.team'),
                    'template' => '<a href="{url}">{label}<span class="fa arrow"></span></a>',
                    'items' => [
                        [
                            'label' => Yii::t('backend', 'views.layout.main.label.team'),
                            'url' => ['team/index'],
                        ],
                        [
                            'label' => Yii::t('backend', 'views.layout.main.label.team-request'),
                            'url' => ['team-request/index'],
                        ],
                    ],
                    'url' => 'javascript:',
                ],
                [
                    'label' => Yii::t('backend', 'views.layout.main.label.news'),
                    'template' => '<a href="{url}">{label}<span class="fa arrow"></span></a>',
                    'items' => [
                        [
                            'label' => Yii::t('backend', 'views.layout.main.label.news'),
                            'url' => ['news/index'],
                        ],
                    ],
                    'url' => 'javascript:',
                ],
                [
                    'label' => Yii::t('backend', 'views.layout.main.label.rule'),
                    'url' => ['rule/index'],
                ],
                [
                    'label' => Yii::t('backend', 'views.layout.main.label.vote'),
                    'url' => ['vote/index'],
                ],
                [
                    'label' => Yii::t('backend', 'views.layout.main.label.schedule'),
                    'url' => ['schedule/index'],
                ],
                [
                    'label' => Yii::t('backend', 'views.layout.main.label.metrics'),
                    'template' => '<a href="{url}">{label}<span class="fa arrow"></span></a>',
                    'items' => [
                        [
                            'label' => Yii::t('backend', 'views.layout.main.label.generator'),
                            'url' => ['analytics/generator-correction'],
                        ],
                        [
                            'label' => Yii::t('backend', 'views.layout.main.label.snapshot'),
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
    <div id="page-wrapper">
        <?php

        try {
            print Breadcrumbs::widget([
                'links' => $this->params['breadcrumbs'] ?? [],
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
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
