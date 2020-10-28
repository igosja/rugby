<?php

/**
 * @var string $content
 * @var AbstractController $context
 * @var View $this
 */

use common\components\helpers\ErrorHelper;
use frontend\assets\AppAsset;
use frontend\components\widgets\Alert;
use frontend\components\widgets\Menu;
use frontend\controllers\AbstractController;
use frontend\models\preparers\SitePrepare;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\web\View;

AppAsset::register($this);
$context = $this->context;

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
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <?php
    if (YII_ENV_PROD) : ?>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-90926144-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('js', new Date());
            gtag('config', 'UA-90926144-1');
        </script>
        <!-- /Global site tag (gtag.js) - Google Analytics -->
        <!--LiveInternet counter-->
        <script type="text/javascript">
            new Image().src = "//counter.yadro.ru/hit?r" +
                escape(document.referrer) + ((typeof (screen) === "undefined") ? "" :
                    ";s" + screen.width + "*" + screen.height + "*" + (screen.colorDepth ?
                    screen.colorDepth : screen.pixelDepth)) + ";u" + escape(document.URL) +
                ";h" + escape(document.title.substring(0, 150)) +
                ";" + Math.random();
        </script>
        <!--/LiveInternet-->
        <!-- fb1ddcd0fe2ed10ac5f2f029a4c98dc5d17b9bea -->
    <?php if (!$context->user || !$context->user->isVip()) : ?>
        <!-- Google AdSense -->
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <!-- /Google AdSense -->
    <?php endif; ?>
    <?php endif; ?>
</head>
<body>
<?php $this->beginBody(); ?>
<div class="main">
    <div class="content">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-left xs-text-center">
                <?= Html::a(
                    Html::img(
                        '/img/logo.png',
                        ['alt' => 'Виртуальная Регбийная Лига', 'class' => 'img-responsive']
                    ),
                    ['site/index']
                ) ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right xs-text-center">
                <br/>
                <?php
                if (Yii::$app->user->isGuest): ?>
                    <?= Html::a(
                        'Войти',
                        ['site/sign-in'],
                        ['class' => 'btn margin']
                    ) ?>
                <?php
                else: ?>
                    <?php
                    $teamArray = [];
                    foreach ($context->myTeamArray as $myTeam) {
                        $teamArray[$myTeam->team_id] = $myTeam->team_name
                            . ' ('
                            . $myTeam->stadium->city->country->country_name
                            . ($myTeam->team_vice_id === $context->user->user_id ? ', ' . 'зам' : '')
                            . ')';
                    }
                    ?>
                    <?= Html::beginForm(['team/change-my-team'], 'post', ['class' => 'form-inline']); ?>
                    <?= Html::dropDownList(
                        'teamId',
                        $context->myTeamOrVice ? $context->myTeamOrVice->team_id : 0,
                        $teamArray,
                        ['class' => 'form-control', 'onchange' => 'this.form.submit();']
                    ) ?>
                    <?= Html::a(
                        'Выйти',
                        ['site/sign-out'],
                        ['class' => ['btn', 'margin']]
                    ) ?>
                    <?= Html::endForm() ?>
                <?php
                endif ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 text-center menu">
                <?php

                try {
                    print Menu::widget();
                } catch (Exception $e) {
                    ErrorHelper::log($e);
                }

                ?>
            </div>
        </div>
        <noscript>
            <div class="row margin-top">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert warning">
                    JavaScript is disabled in your browser. For the site to work correctly, it is recommended to enable
                    JavaScript.
                </div>
            </div>
        </noscript>
        <?php

        try {
            print Alert::widget();
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        ?>
        <?= $content; ?>
        <?php if (YII_ENV_PROD && (!$context->user || !$context->user->isVip())) : ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 footer text-center">
                    <?= Html::tag(
                        'ins',
                        '',
                        [
                            'class' => 'adsbygoogle',
                            'data' => [
                                'ad-client' => 'ca-pub-2661040610443010',
                                'ad-format' => 'auto',
                                'ad-slot' => '9696110553',
                                'full-width-responsive' => 'true',
                            ],
                            'style' => ['display' => 'block'],
                        ]
                    ) ?>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                </div>
            </div>
        <?php
        endif; ?>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 footer text-center">
            Страница сгенерирована за <?= round(Yii::getLogger()->getElapsedTime(), 5) ?> сек,
            <br/>
            <?php
            try {
                print SitePrepare::getSiteVersion();
            } catch (InvalidConfigException $e) {
                ErrorHelper::log($e);
            }
            ?>
        </div>
    </div>
</div>
<?php
$this->endBody(); ?>
</body>
</html>
<?php
$this->endPage(); ?>
