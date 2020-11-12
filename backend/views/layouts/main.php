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
<?php

// TODO refactor
$this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php

// TODO refactor
    $this->head() ?>
</head>
<body>
<?php

// TODO refactor
$this->beginBody() ?>
<div id="wrapper">
    <?php

// TODO refactor
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
                    'label' => FAS::icon(FAS::_FOOTBALL_BALL) . ' Team requests <span class="badge"></span>',
                    'url' => ['team-request/index'],
                ],
                [
                    'label' => FAS::icon(FAS::_SHIELD_ALT) . ' Logos <span class="badge admin-logo"></span>',
                    'url' => ['logo/index'],
                ],
                [
                    'label' => FAS::icon(FAS::_USER) . ' Photos <span class="badge admin-photo"></span>',
                    'url' => ['photo/index'],
                ],
                [
                    'label' => FAS::icon(FAS::_COMMENTS) . ' Support <span class="badge admin-support"></span>',
                    'url' => ['support/index'],
                ],
                [
                    'label' => FAS::icon(
                            FAS::_EXCLAMATION_CIRCLE
                        ) . ' Complaints <span class="badge admin-complaint"></span>',
                    'url' => ['complaint/index'],
                ],
                [
                    'label' => FAS::icon(FAS::_CHART_BAR) . ' Votes <span class="badge admin-vote"></span>',
                    'url' => ['vote/index'],
                ],
            ],
            'url' => 'javascript:',
        ],
        [
            'label' => FAS::icon(FAS::_COG),
            'items' => [
                [
                    'label' => FAS::icon(FAS::_POWER_OFF) . ' Turn ' . (SiteQuery::getStatus() ? 'off' : 'on'),
                    'url' => ['site/status'],
                ],
                [
                    'label' => FAS::icon(FAS::_SIGNAL) . ' Site version',
                    'url' => ['site/version'],
                ],
                [
                    'label' => FAS::icon(FAS::_SIGN_OUT_ALT) . ' Logout',
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

    <?php

// TODO refactor
    NavBar::end() ?>
    <div class="navbar-default sidebar">
        <div class="sidebar-nav navbar-collapse">
            <?php

// TODO refactor
            $menuItems = [
                [
                    'label' => 'Users',
                    'template' => '<a href="{url}">{label}<span class="fa arrow"></span></a>',
                    'items' => [
                        [
                            'label' => 'Users',
                            'url' => ['user/index'],
                        ],
                    ],
                    'url' => 'javascript:',
                ],
                [
                    'label' => 'Teams',
                    'template' => '<a href="{url}">{label}<span class="fa arrow"></span></a>',
                    'items' => [
                        [
                            'label' => 'Teams',
                            'url' => ['team/index'],
                        ],
                        [
                            'label' => 'Team requests',
                            'url' => ['team-request/index'],
                        ],
                    ],
                    'url' => 'javascript:',
                ],
                [
                    'label' => 'News',
                    'template' => '<a href="{url}">{label}<span class="fa arrow"></span></a>',
                    'items' => [
                        [
                            'label' => 'News',
                            'url' => ['news/index'],
                        ],
                    ],
                    'url' => 'javascript:',
                ],
                [
                    'label' => 'Rules',
                    'url' => ['rule/index'],
                ],
                [
                    'label' => 'Votes',
                    'url' => ['vote/index'],
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

// TODO refactor
        try {
            print Breadcrumbs::widget(
                [
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
<?php

// TODO refactor
$this->endBody() ?>
</body>
</html>
<?php

// TODO refactor
$this->endPage() ?>
