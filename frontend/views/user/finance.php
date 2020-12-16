<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Finance;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var ActiveDataProvider $dataProvider
 * @var int $seasonId
 * @var array $seasonArray
 */

print $this->render('//user/_top');

?>
<?= Html::beginForm('', 'get') ?>
<div class="row margin-top-small">
    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
        <?= $this->render('//user/_user-links') ?>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                <?= Html::label('Сезон', 'season_id') ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <?= Html::dropDownList(
                    'season_id',
                    $seasonId,
                    $seasonArray,
                    ['class' => 'form-control submit-on-change', 'id' => 'season_id']
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
                'footer' => 'Дата',
                'header' => 'Дата',
                'headerOptions' => ['class' => 'col-15'],
                'value' => static function (Finance $model) {
                    return FormatHelper::asDate($model->date);
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-right'],
                'footer' => 'Было',
                'footerOptions' => ['class' => 'hidden-xs'],
                'header' => 'Было',
                'headerOptions' => ['class' => 'col-10 hidden-xs'],
                'value' => static function (Finance $model) {
                    return FormatHelper::asCurrency($model->value_before);
                }
            ],
            [
                'contentOptions' => ['class' => 'text-right'],
                'footer' => '+/-',
                'header' => '+/-',
                'headerOptions' => ['class' => 'col-10'],
                'value' => static function (Finance $model) {
                    return FormatHelper::asCurrency($model->value);
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-right'],
                'footer' => 'Стало',
                'footerOptions' => ['class' => 'hidden-xs'],
                'header' => 'Стало',
                'headerOptions' => ['class' => 'col-10 hidden-xs'],
                'value' => static function (Finance $model) {
                    return FormatHelper::asCurrency($model->value_after);
                }
            ],
            [
                'footer' => 'Комментарий',
                'format' => 'raw',
                'header' => 'Комментарий',
                'value' => static function (Finance $model) {
                    return $model->getText();
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
<?= $this->render('//site/_show-full-table') ?>
