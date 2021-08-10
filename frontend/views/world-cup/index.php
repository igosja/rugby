<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\models\db\Game;
use common\models\db\TournamentType;
use common\models\db\User;
use common\models\db\WorldCup;
use kartik\select2\Select2;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var ActiveDataProvider $dataProvider
 * @var array $divisionArray
 * @var int $divisionId
 * @var Game[] $gameArray
 * @var array $nationalTypeArray
 * @var int $nationalTypeId
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
            <?= Yii::t('frontend', 'views.world-cup.index.h1') ?>
        </h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= $this->render('//world-cup/_national-type-links', ['nationalTypeArray' => $nationalTypeArray]) ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= $this->render('//world-cup/_division-links', ['divisionArray' => $divisionArray]) ?>
    </div>
</div>
<?= Html::beginForm(['world-cup/index'], 'get') ?>
<?= Html::hiddenInput('divisionId', $divisionId) ?>
<?= Html::hiddenInput('nationalTypeId', $nationalTypeId) ?>
<div class="row margin-top-small">
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
<?= Html::endForm() ?>
<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center team-logo-div">
        <?= Html::img(
            '/img/tournament_type/' . TournamentType::NATIONAL . '.png?v=' . filemtime(Yii::getAlias('@webroot') . '/img/tournament_type/' . TournamentType::NATIONAL . '.png'),
            [
                'alt' => Yii::t('frontend', 'views.world-cup.index.alt'),
                'class' => 'country-logo',
                'title' => Yii::t('frontend', 'views.world-cup.index.title'),
            ]
        ) ?>
    </div>
    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <p class="text-justify">
                    <?= Yii::t('frontend', 'views.world-cup.index.p.1') ?>
                </p>
                <p>
                    <?= Yii::t('frontend', 'views.world-cup.index.p.2') ?>
                </p>
            </div>
        </div>
    </div>
</div>
<?= Html::beginForm(
    ['world-cup/index', 'seasonId' => $seasonId, 'divisionId' => $divisionId, 'nationalTypeId' => $nationalTypeId],
    'get'
) ?>
<div class="row margin-top-small">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
        <?= Html::label(Yii::t('frontend', 'views.label.stage'), 'stageId') ?>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <?php

        try {
            print Select2::widget([
                'data' => $stageArray,
                'id' => 'stageId',
                'name' => 'stageId',
                'options' => ['class' => 'submit-on-change'],
                'value' => $stageId,
            ]);
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        ?>
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
                        <?= $item->homeNational->nationalLink() ?>
                        <?= $item->formatAuto() ?>
                    </td>
                    <td class="text-center col-10">
                        <?= Html::a(
                            $item->formatScore(),
                            ['game/view', 'id' => $item->id]
                        ) ?>
                    </td>
                    <td>
                        <?= $item->guestNational->nationalLink() ?>
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
                'header' => Yii::t('frontend', 'views.th.place'),
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.place')],
                'value' => static function (WorldCup $model) {
                    return $model->place;
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.th.team'),
                'format' => 'raw',
                'header' => Yii::t('frontend', 'views.th.team'),
                'value' => static function (WorldCup $model) {
                    return $model->national->nationalLink(true);
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.th.game'),
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.game')],
                'header' => Yii::t('frontend', 'views.th.game'),
                'headerOptions' => ['class' => 'col-5 hidden-xs', 'title' => Yii::t('frontend', 'views.title.game')],
                'value' => static function (WorldCup $model) {
                    return $model->game;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.win'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.win')],
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.win')],
                'label' => Yii::t('frontend', 'views.th.win'),
                'value' => static function (WorldCup $model) {
                    return $model->win;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.draw'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.draw')],
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.draw')],
                'label' => Yii::t('frontend', 'views.th.draw'),
                'value' => static function (WorldCup $model) {
                    return $model->draw;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.loose'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.loose')],
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.loose')],
                'label' => Yii::t('frontend', 'views.th.loose'),
                'value' => static function (WorldCup $model) {
                    return $model->loose;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.th.difference'),
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.title.difference')],
                'headerOptions' => ['class' => 'hidden-xs col-6', 'title' => Yii::t('frontend', 'views.title.difference')],
                'label' => Yii::t('frontend', 'views.th.difference'),
                'value' => static function (WorldCup $model) {
                    return $model->point_for . '-' . $model->point_against;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.bonus'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.bonus')],
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.bonus')],
                'label' => Yii::t('frontend', 'views.th.bonus'),
                'value' => static function (WorldCup $model) {
                    return $model->bonus_loose + $model->bonus_try;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.point'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.point')],
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.point')],
                'label' => Yii::t('frontend', 'views.th.point'),
                'value' => static function (WorldCup $model) {
                    return $model->point;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.vs'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.title.vs')],
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.title.vs')],
                'label' => Yii::t('frontend', 'views.th.vs'),
                'value' => static function (WorldCup $model) {
                    return $model->national->power_vs;
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
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <p>
            <?= Html::a(
                Yii::t('frontend', 'views.world-cup.index.link.statistics'),
                [
                    'world-cup/statistics',
                    'divisionId' => $divisionId,
                    'seasonId' => $seasonId,
                ],
                ['class' => 'btn margin']
            ) ?>
        </p>
    </div>
</div>
