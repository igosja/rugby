<?php

// TODO refactor

use backend\assets\AppAsset;
use common\components\helpers\ErrorHelper;
use common\models\queries\SiteQuery;
use common\widgets\Alert;
use rmrevin\yii\fontawesome\FAR;
use rmrevin\yii\fontawesome\FAS;
use rmrevin\yii\fontawesome\FontAwesome;
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
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <?= Html::a(Yii::$app->name, Yii::$app->homeUrl, ['class' => 'navbar-brand']) ?>
        </div>
        <!-- /.navbar-header -->

        <ul class="nav navbar-top-links navbar-right">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:">
                    <?= FAR::icon(FontAwesome::_BELL) ?>
                    <?= Html::tag('span', '', ['class' => 'badge', 'id' => 'admin-bell', 'data' => ['url' => Url::to(['bell/index'])]]) ?>
                    <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-messages">
                    <li>
                        <?= Html::a(
                            FAS::icon(FontAwesome::_FOOTBALL_BALL)
                            . ' '
                            . Yii::t('backend', 'views.layout.main.label.team-request')
                            . ' <span class="badge"></span>',
                            ['team-request/index']
                        ) ?>
                    </li>
                    <li>
                        <?= Html::a(
                            FAS::icon(FontAwesome::_SHIELD_ALT)
                            . ' '
                            . Yii::t('backend', 'views.layout.main.label.logo')
                            . ' <span class="badge admin-logo"></span>',
                            ['logo/index']
                        ) ?>
                    </li>
                    <li>
                        <?= Html::a(
                            FAS::icon(FontAwesome::_USER)
                            . ' '
                            . Yii::t('backend', 'views.layout.main.label.photo')
                            . ' <span class="badge admin-photo"></span>',
                            ['photo/index']
                        ) ?>
                    </li>
                    <li>
                        <?= Html::a(
                            FAS::icon(FontAwesome::_COMMENTS)
                            . ' '
                            . Yii::t('backend', 'views.layout.main.label.support')
                            . ' <span class="badge admin-support"></span>',
                            ['support/index']
                        ) ?>
                    </li>
                    <li>
                        <?= Html::a(
                            FAS::icon(FontAwesome::_EXCLAMATION_CIRCLE)
                            . ' '
                            . Yii::t('backend', 'views.layout.main.label.complaint')
                            . ' <span class="badge admin-complaint"></span>',
                            ['complaint/index']
                        ) ?>
                    </li>
                    <li>
                        <?= Html::a(
                            FAS::icon(FontAwesome::_CHART_BAR)
                            . ' '
                            . Yii::t('backend', 'views.layout.main.label.vote')
                            . ' <span class="badge admin-vote"></span>',
                            ['vote/index']
                        ) ?>
                    </li>
                </ul>
                <!-- /.dropdown-messages -->
            </li>
            <!-- /.dropdown -->
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:">
                    <?= FAR::icon(FontAwesome::_FILE_ALT) ?>
                    <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-messages">
                    <li>
                        <?= Html::a(
                            FAS::icon(FontAwesome::_FILE_ALT) . ' ' . Yii::t('backend', 'views.layout.main.label.log'),
                            ['log/index']
                        ) ?>
                    </li>
                </ul>
                <!-- /.dropdown-messages -->
            </li>
            <!-- /.dropdown -->
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:">
                    <?= FAS::icon(FontAwesome::_COG) ?>
                    <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-messages">
                    <li>
                        <?= Html::a(
                            FAS::icon(FontAwesome::_POWER_OFF) . ' ' . (SiteQuery::getStatus() ? Yii::t('backend', 'views.layout.main.label.turn-off') : Yii::t('backend', 'views.layout.main.label.turn-on')),
                            ['site/status']
                        ) ?>
                    </li>
                    <li>
                        <?= Html::a(
                            FAS::icon(FontAwesome::_SIGNAL) . ' ' . Yii::t('backend', 'views.layout.main.label.version'),
                            ['site/version']
                        ) ?>
                    </li>
                    <li>
                        <?= Html::a(
                            FAS::icon(FontAwesome::_SIGN_OUT_ALT) . ' ' . Yii::t('backend', 'views.layout.main.label.logout'),
                            ['site/logout']
                        ) ?>
                    </li>
                </ul>
                <!-- /.dropdown-messages -->
            </li>
            <!-- /.dropdown -->
        </ul>
        <!-- /.navbar-top-links -->
        <div class="navbar-default sidebar" role="navigation">
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
                    [
                        'label' => 'Map',
                        'template' => '<a href="{url}">{label}<span class="fa arrow"></span></a>',
                        'items' => [
                            [
                                'label' => 'I was',
                                'url' => ['map/index'],
                            ],
                            [
                                'label' => 'I was with wife',
                                'url' => ['map/family'],
                            ],
                            [
                                'label' => 'I want',
                                'url' => ['map/plan'],
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
    </nav>

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
