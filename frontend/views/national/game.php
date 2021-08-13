<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Game;
use common\models\db\National;
use kartik\select2\Select2;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var ActiveDataProvider $dataProvider
 * @var array $seasonArray
 * @var int $seasonId
 * @var National $national
 * @var View $this
 * @var int $totalPoint
 */

?>
<div class="row margin-top">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?= $this->render('//national/_national-top-left', ['national' => $national]) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
        <?= $this->render('//national/_national-top-right', ['national' => $national]) ?>
    </div>
</div>
<?= Html::beginForm(['national/game', 'id' => $national->id], 'get') ?>
<div class="row margin-top-small">
    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
        <?= $this->render('//national/_national-links') ?>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                <?= Html::label(Yii::t('frontend', 'views.label.season'), 'seasonId') ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
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
                'footer' => Yii::t('frontend', 'views.national.game.th.date'),
                'headerOptions' => ['class' => 'col-15'],
                'label' => Yii::t('frontend', 'views.national.game.th.date'),
                'value' => static function (Game $model) {
                    return FormatHelper::asDate($model->schedule->date);
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.national.game.th.tournament'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'headerOptions' => ['class' => 'col-30 hidden-xs'],
                'label' => Yii::t('frontend', 'views.national.game.th.tournament'),
                'value' => static function (Game $model) {
                    return $model->schedule->tournamentType->name;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.national.game.th.stage'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'headerOptions' => ['class' => 'col-10 hidden-xs'],
                'label' => Yii::t('frontend', 'views.national.game.th.stage'),
                'value' => static function (Game $model) {
                    return $model->schedule->stage->name;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.national.game.title.home-guest')],
                'headerOptions' => ['class' => 'col-1 hidden-xs', 'title' => Yii::t('frontend', 'views.national.game.title.home-guest')],
                'value' => static function (Game $model) use ($national) {
                    return $model->gameHomeGuest($national);
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.national.game.th.power-percent'),
                'footerOptions' => [
                    'class' => 'hidden-xs',
                    'title' => Yii::t('frontend', 'views.national.game.title.power-percent')
                ],
                'headerOptions' => [
                    'class' => 'col-5 hidden-xs',
                    'title' => Yii::t('frontend', 'views.national.game.title.power-percent')
                ],
                'label' => Yii::t('frontend', 'views.national.game.th.power-percent'),
                'value' => static function (Game $model) use ($national) {
                    return $model->gamePowerPercent($national);
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.national.game.th.opponent'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.national.game.th.opponent'),
                'value' => static function (Game $model) use ($national) {
                    return $model->opponentLink($national);
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.national.game.th.auto'),
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.national.game.title.auto')],
                'headerOptions' => ['class' => 'col-1 hidden-xs', 'title' => Yii::t('frontend', 'views.national.game.title.auto')],
                'label' => Yii::t('frontend', 'views.national.game.th.auto'),
                'value' => static function (Game $model) use ($national): string {
                    return $model->gameAuto($national);
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.national.game.th.score'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.national.game.th.score'),
                'value' => static function (Game $model) use ($national): string {
                    return Html::a(
                        $model->formatTeamScore($national),
                        ['game/view', 'id' => $model->id]
                    );
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => $totalPoint,
                'footerOptions' => ['class' => 'hidden-xs', 'title' => Yii::t('frontend', 'views.national.game.title.plus-minus')],
                'headerOptions' => ['class' => 'col-1 hidden-xs', 'title' => Yii::t('frontend', 'views.national.game.title.plus-minus')],
                'value' => static function (Game $model) use ($national): string {
                    return $model->gamePlusMinus($national);
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
        <?= $this->render('//national/_national-links') ?>
    </div>
</div>
<?= $this->render('//site/_show-full-table') ?>
