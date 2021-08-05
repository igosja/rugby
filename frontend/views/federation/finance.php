<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Federation;
use common\models\db\Finance;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var ActiveDataProvider $dataProvider
 * @var Federation $federation
 * @var array $seasonArray
 * @var int $seasonId
 * @var View $this
 */

print $this->render('_federation', [
    'federation' => $federation,
]);

?>
<?= Html::beginForm(['country/finance', 'id' => Yii::$app->request->get('id')], 'get') ?>
<div class="row margin-top-small">
    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-8"></div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                <?= Html::label(Yii::t('frontend', 'views.label.season'), 'season_id') ?>
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
<?= $this->render('//site/_show-full-table') ?>
