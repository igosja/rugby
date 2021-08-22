<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Player;
use common\models\db\Team;
use frontend\controllers\AbstractController;
use rmrevin\yii\fontawesome\FAS;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/**
 * @var AbstractController $controller
 * @var ActiveDataProvider $dataProvider
 * @var array $notificationArray
 * @var Team $team
 * @var View $this
 */

$controller = Yii::$app->controller;

?>
<div class="row margin-top">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?= $this->render('//team/_team-top-left', ['team' => $team]) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
        <?= $this->render('//team/_team-top-right', ['team' => $team]) ?>
    </div>
</div>
<?php if ($notificationArray) : ?>
    <div class="row margin-top">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <ul>
                <?php foreach ($notificationArray as $item) : ?>
                    <li><?= $item ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    </div>
<?php endif ?>
<div class="row margin-top-small">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//team/_team-links', ['id' => $team->id]) ?>
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'class' => SerialColumn::class,
                'contentOptions' => ['class' => 'text-center'],
                'footer' => '#',
                'header' => '#',
            ],
            [
                'attribute' => 'squad',
                'footer' => Yii::t('frontend', 'views.th.player'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.th.player'),
                'value' => static function (Player $model) {
                    return $model->getPlayerLink()
                        . $model->iconPension()
                        . $model->iconInjury()
                        . $model->iconNational()
                        . $model->iconDeal()
                        . $model->iconTraining()
                        . $model->iconLoan()
                        . $model->iconScout();
                }
            ],
            [
                'attribute' => 'country',
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.th.national'),
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.national')],
                'format' => 'raw',
                'headerOptions' => ['class' => 'hidden-xs col-1', 'title' => Yii::t('frontend', 'views.title.national')],
                'label' => Yii::t('frontend', 'views.th.national'),
                'value' => static function (Player $model) {
                    return $model->country->getImageLink();
                }
            ],
            [
                'attribute' => 'position',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.position'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.position')],
                'format' => 'raw',
                'headerOptions' => ['title' => Yii::t('frontend', 'views.title.position')],
                'label' => Yii::t('frontend', 'views.th.position'),
                'value' => static function (Player $model) {
                    return $model->position();
                }
            ],
            [
                'attribute' => 'age',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.age'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.age')],
                'headerOptions' => ['title' => Yii::t('frontend', 'views.title.age')],
                'label' => Yii::t('frontend', 'views.th.age'),
                'value' => static function (Player $model) {
                    return $model->age;
                }
            ],
            [
                'attribute' => 'power_nominal',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.nominal-power'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.nominal-power')],
                'format' => 'raw',
                'headerOptions' => ['title' => Yii::t('frontend', 'views.title.nominal-power')],
                'label' => Yii::t('frontend', 'views.th.nominal-power'),
                'value' => static function (Player $model) {
                    return $model->powerNominal();
                }
            ],
            [
                'attribute' => 'tire',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.tire'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.tire')],
                'headerOptions' => ['title' => Yii::t('frontend', 'views.title.tire')],
                'label' => Yii::t('frontend', 'views.th.tire'),
                'value' => static function (Player $model) {
                    return $model->playerTire();
                }
            ],
            [
                'attribute' => 'physical',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.physical'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.physical')],
                'format' => 'raw',
                'headerOptions' => ['title' => Yii::t('frontend', 'views.title.physical')],
                'label' => Yii::t('frontend', 'views.th.physical'),
                'value' => static function (Player $model) {
                    return $model->playerPhysical();
                }
            ],
            [
                'attribute' => 'power_real',
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.real-power'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.real-power')],
                'headerOptions' => ['title' => Yii::t('frontend', 'views.title.real-power')],
                'label' => Yii::t('frontend', 'views.th.real-power'),
                'value' => static function (Player $model) use ($team) {
                    return $team->myTeam() ? $model->power_real : '~' . $model->power_nominal;
                }
            ],
            [
                'attribute' => 'special',
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.th.special'),
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.special')],
                'format' => 'raw',
                'headerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.special')],
                'label' => Yii::t('frontend', 'views.th.special'),
                'value' => static function (Player $model) {
                    return $model->special();
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.team.view.th.style'),
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.team.view.title.style')],
                'format' => 'raw',
                'headerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.team.view.title.style')],
                'label' => Yii::t('frontend', 'views.team.view.th.style'),
                'value' => static function (Player $model) {
                    return $model->iconStyle(true);
                }
            ],
            [
                'attribute' => 'game_row',
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.th.row'),
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.row')],
                'headerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.row')],
                'label' => Yii::t('frontend', 'views.th.row'),
                'value' => static function (Player $model) {
                    return $model->playerGameRow();
                }
            ],
            [
                'attribute' => 'game',
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.th.game'),
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.game')],
                'headerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.game')],
                'label' => Yii::t('frontend', 'views.th.game'),
                'value' => static function () {
                    return 0;
                }
            ],
            [
                'attribute' => 'price',
                'contentOptions' => ['class' => 'hidden-xs text-right'],
                'footer' => Yii::t('frontend', 'views.th.price'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'headerOptions' => ['class' => 'hidden-xs'],
                'label' => Yii::t('frontend', 'views.th.price'),
                'value' => static function (Player $model) {
                    return FormatHelper::asCurrency($model->price);
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $dataProvider,
            'rowOptions' => static function (Player $model) use ($team) {
                $result = [];
                if ($model->squad && $team->myTeam()) {
                    $result['style']['background-color'] = '#' . $model->squad->color;
                }
                if ($model->is_injury) {
                    $result['class'] = 'font-red';
                }
                return $result;
            },
            'showFooter' => true,
            'summary' => false,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//team/_team-links', ['id' => $team->id]) ?>
    </div>
</div>
<?= $this->render('//site/_show-full-table') ?>
<div class="row margin-top">
    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 text-size-2">
        <span class="italic"><?= Yii::t('frontend', 'views.team.view.values') ?>:</span>
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                - <?= Yii::t('frontend', 'views.team.view.vs') ?>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                <?= $team->power_vs ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                - <?= Yii::t('frontend', 'views.team.view.s15') ?>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                <?= $team->power_s_15 ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                - <?= Yii::t('frontend', 'views.team.view.s19') ?>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                <?= $team->power_s_19 ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                - <?= Yii::t('frontend', 'views.team.view.s24') ?>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                <?= $team->power_s_24 ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                - <?= Yii::t('frontend', 'views.team.view.price.base') ?>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                <?= FormatHelper::asCurrency($team->price_base) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                - <?= Yii::t('frontend', 'views.team.view.price.total') ?>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                <?= FormatHelper::asCurrency($team->price_total) ?>
            </div>
        </div>
        <div class="row margin-top">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= Yii::t('frontend', 'views.team.view.share') ?>:
                <p>
                    <?php try {
                        print Html::a(
                                FAS::icon(FAS::_FACEBOOK)->size(FAS::SIZE_2X),
                                'https://www.facebook.com/sharer/sharer.php?u=' . Url::to(['site/index'], true),
                                ['class' => ['no-underline'], 'target' => '_blank']
                            )
                            . ' '
                            . Html::a(
                                FAS::icon(FAS::_TWITTER)->size(FAS::SIZE_2X),
                                'https://twitter.com/intent/tweet?text=' . Yii::t('frontend', 'views.team.view.text') . '&url=' . Url::to(['site/index'], true),
                                ['class' => ['no-underline'], 'target' => '_blank']
                            );
                    } catch (InvalidConfigException $e) {
                        ErrorHelper::log($e);
                    } ?>
                </p>
            </div>
        </div>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 hidden-xs"></div>
    <?php if ($team->myTeam()) : ?>
        <?= $this->render('_team-bottom-forum', ['team' => $team]) ?>
    <?php elseif ($controller->myTeam): ?>
        <?= $this->render('_team-bottom-my-team') ?>
    <?php endif ?>
</div>