<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Schedule;
use common\models\db\Team;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var ActiveDataProvider $dataProvider
 * @var array $scheduleStatusArray
 * @var Team $team
 */

?>
<div class="row margin-top">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong text-size-1">
                <?= $team->fullName() ?>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= Html::a(
                    $team->friendlyStatus->name,
                    ['status']
                ) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong text-right text-size-1">
                <?= Yii::t('frontend', 'views.friendly.index.friendly') ?>
            </div>
        </div>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong">
        <?= Yii::t('frontend', 'views.friendly.index.days') ?>:
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-25'],
                'label' => Yii::t('frontend', 'views.friendly.index.th.day'),
                'value' => static function (Schedule $model) {
                    return Html::a(
                        FormatHelper::asDate($model->date),
                        ['view', 'id' => $model->id]
                    );
                }
            ],
            [
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.friendly.index.th.status'),
                'value' => static function (Schedule $model) use ($scheduleStatusArray) {
                    return $scheduleStatusArray[$model->id];
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $dataProvider,
            'emptyText' => Yii::t('frontend', 'views.friendly.index.empty'),
            'summary' => false,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
