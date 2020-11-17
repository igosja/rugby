<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Schedule;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
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
        <h1>Расписание</h1>
    </div>
</div>
<?= Html::beginForm(null, 'get') ?>
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
        <?= Html::label('Сезон', 'seasonId') ?>
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
    <?php

// TODO refactor

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Дата',
                'headerOptions' => ['class' => 'col-20'],
                'label' => 'Дата',
                'value' => static function (Schedule $model) {
                    return FormatHelper::asDateTime($model->date);
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Турнир',
                'format' => 'raw',
                'label' => 'Турнир',
                'value' => static function (Schedule $model) {
                    return Html::a(
                        $model->tournamentType->name,
                        ['view', 'id' => $model->id]
                    );
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => 'Стадия',
                'footerOptions' => ['class' => 'hidden-xs'],
                'headerOptions' => ['class' => 'col-20 hidden-xs'],
                'label' => 'Стадия',
                'value' => static function (Schedule $model) {
                    return $model->stage->name;
                }
            ],
        ];
        print GridView::widget([
                'columns' => $columns,
                'dataProvider' => $dataProvider,
                'rowOptions' => static function (Schedule $model) use ($scheduleId) {
                    if (in_array($model->id, $scheduleId, true)) {
                        return ['class' => 'info'];
                    }
                    return [];
                },
                'showFooter' => true,
                'summary' => false,
            ]
        );
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
<?= $this->render('//site/_show-full-table') ?>
