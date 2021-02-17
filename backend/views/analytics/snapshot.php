<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use miloschuman\highcharts\Highcharts;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * @var array $categoryArray
 * @var array $dataArray
 * @var int $id
 * @var array $seasonArray
 * @var int $seasonId
 * @var array $valueArray
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <h3 class="page-header"><?= Html::encode($this->title) ?></h3>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= Html::beginForm(['analytics/snapshot'], 'get', ['class' => 'form-inline']) ?>
        <div class="form-group">
            <label for="num"><?= Html::label(Yii::t('frontend', 'views.label.season'), 'seasonId') ?></label>
            <?= Html::dropDownList(
                'seasonId',
                $seasonId,
                $seasonArray,
                ['class' => 'form-control', 'id' => 'seasonId']
            ) ?>
        </div>
        <div class="form-group">
            <label for="num"><?= Html::label(Yii::t('backend', 'views.analytics.snapshot.label.id'), 'id') ?></label>
            <?= Html::dropDownList(
                'id',
                $id,
                ArrayHelper::map($categoryArray, 'id', 'name'),
                ['class' => 'form-control', 'id' => 'id']
            ) ?>
        </div>
        <?= Html::submitButton(Yii::t('backend', 'views.analytics.snapshot.submit'), ['class' => 'btn btn-default']) ?>
        <?= Html::endForm() ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php

        try {
            print Highcharts::widget([
                'options' => [
                    'credits' => [
                        'enabled' => false,
                    ],
                    'legend' => [
                        'enabled' => false,
                    ],
                    'series' => [
                        [
                            'name' => $categoryArray[$id]['name'],
                            'data' => $valueArray,
                        ]
                    ],
                    'title' => [
                        'text' => $categoryArray[$id]['name'],
                    ],
                    'tooltip' => [
                        'headerFormat' => '<b>{point.key}</b><br/>',
                        'pointFormat' => '{series.name} <b>{point.y}</b>',
                    ],
                    'xAxis' => [
                        'categories' => $dataArray,
                        'title' => [
                            'text' => Yii::t('backend', 'views.analytics.snapshot.date'),
                        ],
                    ],
                    'yAxis' => [
                        'title' => [
                            'text' => $categoryArray[$id]['name'],
                        ],
                    ],
                ]
            ]);
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        ?>
    </div>
</div>
