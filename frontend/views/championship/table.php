<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\models\db\Championship;
use common\models\db\Federation;
use common\models\db\Game;
use common\models\db\User;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var Federation $federation
 * @var ActiveDataProvider $dataProvider
 * @var array $divisionArray
 * @var int $divisionId
 * @var Game[] $gameArray
 * @var array $scheduleId
 * @var array $seasonArray
 * @var int $seasonId
 * @var array $stageArray
 * @var int $stageId
 * @var User $user
 */

$user = Yii::$app->user->identity;

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h1>
            <?= Html::a(
                $federation->country->name,
                ['federation/news', 'id' => $federation->country->id],
                ['class' => 'country-header-link']
            ) ?>
        </h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= $this->render('//championship/_division-links', ['divisionArray' => $divisionArray]) ?>
    </div>
</div>
<?= Html::beginForm(null, 'get') ?>
<?= Html::hiddenInput('divisionId', $divisionId) ?>
<?= Html::hiddenInput('federationId', $federation->id) ?>
<div class="row margin-top-small">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
        <?= Html::label(Yii::t('frontend', 'views.label.season'), 'seasonId') ?>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <?= Html::dropDownList(
            'seasonId',
            $seasonId,
            $seasonArray,
            ['class' => 'form-control submit-on-change', 'id' => 'seasonId']
        ) ?>
    </div>
    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-4"></div>
</div>
<?= Html::endForm() ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-justify">
            <?= Yii::t('frontend', 'views.championship.table.p.1') ?>
        </p>
        <p class="text-justify">
            <?= Yii::t('frontend', 'views.championship.table.p.2') ?>
        </p>
    </div>
</div>
<?= Html::beginForm(
    ['', 'federationId' => $federation->id, 'seasonId' => $seasonId, 'divisionId' => $divisionId],
    'get'
) ?>
<div class="row margin-top-small">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
        <?= Html::label(Yii::t('frontend', 'views.label.stage'), 'stageId') ?>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <?= Html::dropDownList(
            'stageId',
            $stageId,
            $stageArray,
            ['class' => 'form-control submit-on-change', 'id' => 'stageId']
        ) ?>
    </div>
    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-4"></div>
</div>
<?= Html::endForm() ?>
<div class="row margin-top-small">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table class="table">
            <?php foreach ($gameArray as $item) : ?>
                <tr>
                    <td class="text-right col-45">
                        <?= $item->homeTeam->getTeamLink() ?>
                        <?= $item->formatAuto() ?>
                    </td>
                    <td class="text-center col-10">
                        <?= Html::a(
                            $item->formatScore(),
                            ['game/view', 'id' => $item->id]
                        ) ?>
                    </td>
                    <td>
                        <?= $item->guestTeam->getTeamLink() ?>
                        <?= $item->formatAuto('guest') ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</div>
<div class="row margin-top-small">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.place'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.place')],
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.place')],
                'label' => Yii::t('frontend', 'views.th.place'),
                'value' => static function (Championship $model) {
                    return $model->place;
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.th.team'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.th.team'),
                'value' => static function (Championship $model) {
                    return $model->team->iconFreeTeam() . $model->team->getTeamLink();
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.th.game'),
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.game')],
                'headerOptions' => ['class' => 'col-5 hidden-xs', 'title' => Yii::t('frontend', 'views.title.game')],
                'label' => Yii::t('frontend', 'views.th.game'),
                'value' => static function (Championship $model) {
                    return $model->game;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.win'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.win')],
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.win')],
                'label' => Yii::t('frontend', 'views.th.win'),
                'value' => static function (Championship $model) {
                    return $model->win;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.draw'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.draw')],
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.draw')],
                'label' => Yii::t('frontend', 'views.th.draw'),
                'value' => static function (Championship $model) {
                    return $model->draw;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.loose'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.loose')],
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.loose')],
                'label' => Yii::t('frontend', 'views.th.loose'),
                'value' => static function (Championship $model) {
                    return $model->loose;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.th.difference'),
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.difference')],
                'headerOptions' => ['class' => 'hidden-xs col-6', 'title' => Yii::t('frontend', 'views.title.difference')],
                'label' => Yii::t('frontend', 'views.th.difference'),
                'value' => static function (Championship $model) {
                    return $model->point_for . '-' . $model->point_against;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.bonus'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.bonus')],
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.bonus')],
                'label' => Yii::t('frontend', 'views.th.bonus'),
                'value' => static function (Championship $model) {
                    return $model->bonus_loose + $model->bonus_try;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.point'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.point')],
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.power')],
                'label' => Yii::t('frontend', 'views.th.point'),
                'value' => static function (Championship $model) {
                    return $model->point;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.vs'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.vs')],
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.vs')],
                'label' => Yii::t('frontend', 'views.th.vs'),
                'value' => static function (Championship $model) {
                    return $model->team->power_vs;
                },
                'visible' => $user && $user->isVip(),
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $dataProvider,
            'rowOptions' => static function (Championship $model) {
                $class = '';
                $title = '';
                if ($model->place >= 15) {
                    $class = 'tournament-table-down';
                    $title = Yii::t('frontend', 'views.championship.table.down');
                }
                return ['class' => $class, 'title' => $title];
            },
            'showFooter' => true,
            'summary' => false,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
<?= $this->render('//site/_show-full-table') ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <p>
            <?= Html::a(
                Yii::t('frontend', 'views.championship.table.link.statistics'),
                [
                    'championship/statistics',
                    'federationId' => $federation->id,
                    'divisionId' => $divisionId,
                    'seasonId' => $seasonId,
                ],
                ['class' => 'btn margin']
            ) ?>
        </p>
    </div>
</div>
