<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\models\db\Conference;
use common\models\db\User;
use kartik\select2\Select2;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var array $countryArray
 * @var int $federationId
 * @var ActiveDataProvider $dataProvider
 * @var array $seasonArray
 * @var int $seasonId
 * @var User $user
 */

$user = $this->context->user;

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h1><?= Yii::t('frontend', 'views.conference.table.h1') ?></h1>
    </div>
</div>
<?= Html::beginForm(null, 'get') ?>
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
        <?= Html::label(Yii::t('frontend', 'views.label.season'), 'seasonId') ?>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <?php

        try {
            print Select2::widget([
                'data' => $seasonArray,
                'id' => 'seasonId',
                'name' => 'seasonId',
                'options' => ['class' => 'submit-on-change'],
                'value' => $seasonId,
            ]);
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        ?>
    </div>
    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-4"></div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
        <?= Html::label(Yii::t('frontend', 'views.conference.table.label.federation'), 'federationId') ?>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <?php

        try {
            print Select2::widget([
                'data' => $countryArray,
                'id' => 'federationId',
                'name' => 'federationId',
                'options' => ['class' => 'submit-on-change', 'prompt' => Yii::t('frontend', 'views.prompt.all')],
                'value' => $federationId,
            ]);
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        ?>
    </div>
    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-4"></div>
</div>
<?= Html::endForm() ?>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.place'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.place')],
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.place')],
                'label' => Yii::t('frontend', 'views.th.place'),
                'value' => static function (Conference $model) {
                    return $model->place;
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.th.team'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.th.team'),
                'value' => static function (Conference $model) {
                    return $model->team->iconFreeTeam() . $model->team->getTeamImageLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.th.game'),
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.game')],
                'headerOptions' => ['class' => 'col-5 hidden-xs', 'title' => Yii::t('frontend', 'views.title.game')],
                'label' => Yii::t('frontend', 'views.th.game'),
                'value' => static function (Conference $model) {
                    return $model->game;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.win'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.win')],
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.win')],
                'label' => Yii::t('frontend', 'views.th.win'),
                'value' => static function (Conference $model) {
                    return $model->win;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.draw'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.draw')],
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.draw')],
                'label' => Yii::t('frontend', 'views.th.draw'),
                'value' => static function (Conference $model) {
                    return $model->draw;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.loose'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.loose')],
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.loose')],
                'label' => Yii::t('frontend', 'views.th.loose'),
                'value' => static function (Conference $model) {
                    return $model->loose;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.th.difference'),
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.difference')],
                'headerOptions' => ['class' => 'hidden-xs col-6', 'title' => Yii::t('frontend', 'views.title.difference')],
                'label' => Yii::t('frontend', 'views.th.difference'),
                'value' => static function (Conference $model) {
                    return $model->point_for . '-' . $model->point_against;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.bonus'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.bonus')],
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.bonus')],
                'label' => Yii::t('frontend', 'views.th.bonus'),
                'value' => static function (Conference $model) {
                    return $model->bonus_loose + $model->bonus_try;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.point'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.point')],
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.point')],
                'label' => Yii::t('frontend', 'views.th.point'),
                'value' => static function (Conference $model) {
                    return $model->point;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.vs'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.vs')],
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.vs')],
                'label' => Yii::t('frontend', 'views.th.vs'),
                'value' => static function (Conference $model) {
                    return $model->team->power_vs;
                },
                'visible' => $user && $user->isVip(),
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $dataProvider,
            'showFooter' => true,
            'summary' => false,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
<?= $this->render('//site/_show-full-table') ?>
