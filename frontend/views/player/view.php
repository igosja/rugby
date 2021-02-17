<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Lineup;
use common\models\db\Player;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var int $assist
 * @var ActiveDataProvider $dataProvider
 * @var Player $player
 * @var array $seasonArray
 * @var int $seasonId
 * @var View $this
 */

print $this->render('//player/_player', ['player' => $player]);

?>
<?= Html::beginForm(['player/view', 'id' => $player->id], 'get') ?>
<div class="row margin-top-small">
    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
        <?= $this->render('//player/_links', ['id' => $player->id]) ?>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                <?= Html::label(Yii::t('frontend', 'views.label.season'), 'seasonId') ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <?= Html::dropDownList(
                    'seasonId',
                    $seasonId,
                    $seasonArray,
                    ['class' => 'form-control submit-on-change', 'id' => 'seasonId']
                ) ?>
            </div>
        </div>
    </div>
</div>
<?= Html::endForm() ?>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.player.view.th.date'),
                'headerOptions' => ['class' => 'col-10'],
                'label' => Yii::t('frontend', 'views.player.view.th.date'),
                'value' => static function (Lineup $model) {
                    return FormatHelper::asDate($model->game->schedule->date);
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.player.view.th.game'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.player.view.th.game'),
                'value' => static function (Lineup $model) {
                    return $model->game->teamOrNationalLink('home', false)
                        . '-'
                        . $model->game->teamOrNationalLink('guest', false);
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.player.view.th.score'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.player.view.title.score')],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-5', 'title' => Yii::t('frontend', 'views.player.view.title.score')],
                'label' => Yii::t('frontend', 'views.player.view.th.score'),
                'value' => static function (Lineup $model) {
                    return Html::a(
                        $model->game->formatScore(),
                        ['game/view', 'id' => $model->game->id]
                    );
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.player.view.th.tournament'),
                'headerOptions' => ['class' => 'col-13'],
                'label' => Yii::t('frontend', 'views.player.view.th.tournament'),
                'value' => static function (Lineup $model) {
                    return $model->game->schedule->tournamentType->name;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.player.view.th.stage'),
                'headerOptions' => ['class' => 'col-10'],
                'label' => Yii::t('frontend', 'views.player.view.th.stage'),
                'value' => static function (Lineup $model) {
                    return $model->game->schedule->stage->name;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.player.view.th.position'),
                'footerOptions' => ['title' => Yii::t('frontend', 'views.player.view.title.position')],
                'headerOptions' => ['class' => 'col-10', 'title' => Yii::t('frontend', 'views.player.view.title.position')],
                'label' => Yii::t('frontend', 'views.player.view.th.position'),
                'value' => static function (Lineup $model) {
                    return $model->position_id;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.player.view.th.power'),
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.player.view.title.power')],
                'headerOptions' => ['class' => 'col-5 hidden-xs', 'title' => Yii::t('frontend', 'views.player.view.title.power')],
                'label' => Yii::t('frontend', 'views.player.view.th.power'),
                'value' => static function (Lineup $model) {
                    return $model->power_real;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.player.view.th.point'),
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.player.view.title.point')],
                'headerOptions' => ['class' => 'col-5 hidden-xs', 'title' => Yii::t('frontend', 'views.player.view.title.point')],
                'label' => Yii::t('frontend', 'views.player.view.th.point'),
                'value' => static function (Lineup $model) {
                    return $model->point;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.player.view.title.power-change')],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-5 hidden-xs', 'title' => Yii::t('frontend', 'views.player.view.title.power-change')],
                'value' => static function (Lineup $model) {
                    return $model->iconPowerChange();
                }
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
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//player/_links', ['id' => $player->id]) ?>
    </div>
</div>
<?= $this->render('//site/_show-full-table') ?>
