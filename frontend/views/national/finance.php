<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Finance;
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
<?= Html::beginForm(['national/finance', 'id' => $national->id], 'get') ?>
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
                'footer' => Yii::t('frontend', 'views.th.date'),
                'headerOptions' => ['class' => 'col-15'],
                'label' => Yii::t('frontend', 'views.th.date'),
                'value' => static function (Finance $model) {
                    return FormatHelper::asDate($model->date);
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-right'],
                'footer' => Yii::t('frontend', 'views.finance.th.before'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'headerOptions' => ['class' => 'col-10 hidden-xs'],
                'label' => Yii::t('frontend', 'views.finance.th.before'),
                'value' => static function (Finance $model) {
                    return FormatHelper::asCurrency($model->value_before);
                }
            ],
            [
                'contentOptions' => ['class' => 'text-right'],
                'footer' => Yii::t('frontend', 'views.finance.th.value'),
                'headerOptions' => ['class' => 'col-10'],
                'label' => Yii::t('frontend', 'views.finance.th.value'),
                'value' => static function (Finance $model) {
                    return FormatHelper::asCurrency($model->value);
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-right'],
                'footer' => Yii::t('frontend', 'views.finance.th.after'),
                'footerOptions' => ['class' => 'hidden-xs'],
                'headerOptions' => ['class' => 'col-10 hidden-xs'],
                'label' => Yii::t('frontend', 'views.finance.th.after'),
                'value' => static function (Finance $model) {
                    return FormatHelper::asCurrency($model->value_after);
                }
            ],
            [
                'footer' => Yii::t('frontend', 'views.finance.th.comment'),
                'format' => 'raw',
                'label' => Yii::t('frontend', 'views.finance.th.comment'),
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
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//national/_national-links') ?>
    </div>
</div>
<?= $this->render('//site/_show-full-table') ?>
