<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use frontend\models\forms\LoanTo;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var LoanTo $model
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <p>
            Здесь вы можете <span class="strong">поставить своего игрока на арендный рынок</span>.
        </p>
        <p>
            Начальная цена аренды игрока должна быть не меньше
            <span class="strong"><?= FormatHelper::asCurrency($model->minPrice) ?></span>
            и не больше
            <span class="strong"><?= FormatHelper::asCurrency($model->maxPrice) ?></span>.
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
        <?= $form->field($model, 'minDay', [
            'template' => '
                <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">{label}</div>
                <div class="col-lg-1 col-md-2 col-sm-2 col-xs-6">{input}</div>
                </div>
                <div class="row">{error}</div>'
        ])->textInput() ?>
        <?= $form->field($model, 'maxDay', [
            'template' => '
                <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">{label}</div>
                <div class="col-lg-1 col-md-2 col-sm-2 col-xs-6">{input}</div>
                </div>
                <div class="row">{error}</div>'
        ])->textInput() ?>
        <p class="text-center">
            <?= Html::submitButton('Выставить на рынок аренды', ['class' => 'btn']) ?>
        </p>
        <?php $form::end() ?>
    </div>
</div>