<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Schedule;
use kartik\select2\Select2;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * @var ActiveDataProvider $dataProvider
 * @var array $seasonArray
 * @var int $seasonId
 * @var array $scheduleId
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h1><?= Yii::t('frontend', 'views.schedule.index.h1') ?></h1>
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
<?= Html::endForm() ?>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.date'),
                'headerOptions' => ['class' => 'col-20'],
                'label' => Yii::t('frontend', 'views.th.date'),
                'value' => static function (Schedule $model) {
                    return FormatHelper::asDateTime($model->date);
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => Yii::t('frontend', 'views.th.tournament'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.th.tournament'),
                'value' => static function (Schedule $model) {
                    return Html::a(
                        $model->tournamentType->name,
                        ['view', 'id' => $model->id]
                    );
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.schedule.index.th.stage'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'headerOptions' => ['class' => 'col-20 hidden-xs'],
                'label' => Yii::t('frontend', 'views.schedule.index.th.stage'),
                'value' => static function (Schedule $model) {
                    return $model->stage->name;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => Yii::t('frontend', 'views.schedule.index.th.type'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-5 hidden-xs'],
                'label' => Yii::t('frontend', 'views.schedule.index.th.type'),
                'value' => static function (Schedule $model) {
                    return Html::tag(
                        'span',
                        $model->tournamentType->dayType->name,
                        ['title' => $model->tournamentType->dayType->text]
                    );
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $dataProvider,
            'rowOptions' => static function (Schedule $model) use ($scheduleId) {
                if (ArrayHelper::isIn($model->id, $scheduleId)) {
                    return ['class' => 'info'];
                }
                return [];
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
