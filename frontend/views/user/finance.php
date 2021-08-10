<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Finance;
use kartik\select2\Select2;
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
                <?= Html::label(Yii::t('frontend', 'views.label.season'), 'season_id') ?>
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
                'footer' => Yii::t('frontend', 'views.th.date'),
                'header' => Yii::t('frontend', 'views.th.date'),
                'headerOptions' => ['class' => 'col-15'],
                'value' => static function (Finance $model) {
                    return FormatHelper::asDate($model->date);
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-right'],
                'footer' => Yii::t('frontend', 'views.finance.th.before'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'header' => Yii::t('frontend', 'views.finance.th.before'),
                'headerOptions' => ['class' => 'col-10 hidden-xs'],
                'value' => static function (Finance $model) {
                    return FormatHelper::asCurrency($model->value_before);
                }
            ],
            [
                'contentOptions' => ['class' => 'text-right'],
                'footer' => Yii::t('frontend', 'views.finance.th.value'),
                'header' => Yii::t('frontend', 'views.finance.th.value'),
                'headerOptions' => ['class' => 'col-10'],
                'value' => static function (Finance $model) {
                    return FormatHelper::asCurrency($model->value);
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-right'],
                'footer' => Yii::t('frontend', 'views.finance.th.after'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'header' => Yii::t('frontend', 'views.finance.th.after'),
                'headerOptions' => ['class' => 'col-10 hidden-xs'],
                'value' => static function (Finance $model) {
                    return FormatHelper::asCurrency($model->value_after);
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.finance.th.comment'),
                'format' => 'raw',
                'header' => Yii::t('frontend', 'views.finance.th.comment'),
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
