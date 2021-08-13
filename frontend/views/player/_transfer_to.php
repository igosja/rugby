<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use frontend\models\forms\TransferTo;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var TransferTo $model
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <p>
            <?= Yii::t('frontend', 'views.player.transfer-to.p.1') ?>
        </p>
        <p>
            <?= Yii::t('frontend', 'views.player.transfer-to.p.2', [
                'min' => FormatHelper::asCurrency($model->minPrice),
                'max' => FormatHelper::asCurrency($model->maxPrice)
            ]) ?>
        </p>
        <?php $form = ActiveForm::begin([
            'enableAjaxValidation' => true,
            'fieldConfig' => [
                'errorOptions' => [
                    'class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center notification-error',
                    'tag' => 'div'
                ],
            ],
        ]) ?>
        <?= $form->field($model, 'price', [
            'template' => '
                <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">{label}</div>
                <div class="col-lg-1 col-md-2 col-sm-2 col-xs-6">{input}</div>
                </div>
                <div class="row">{error}</div>'
        ])->textInput() ?>
        <div class="row">
            <?= $form->field($model, 'toLeague', [
                'template' => '
                <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">{input}</div>
                </div>
                <div class="row">{error}</div>'
            ])->checkbox() ?>
        </div>
        <p class="text-center">
            <?= Html::submitButton(Yii::t('frontend', 'views.player.transfer-to.submit'), ['class' => 'btn']) ?>
        </p>
        <?php $form::end() ?>
    </div>
</div>
