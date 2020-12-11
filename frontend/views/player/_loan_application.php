<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use frontend\models\forms\LoanApplicationFrom;
use frontend\models\forms\LoanApplicationTo;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var LoanApplicationTo $model
 * @var LoanApplicationFrom $modelFrom
 */

try {
    $modelFromClassName = $modelFrom->formName();
} catch (Exception $e) {
    ErrorHelper::log($e);
    $modelFromClassName = 'LoanApplicationFrom';
}

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
                Ваша команда:
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <span class="strong">
                    <?= $model->team->getTeamLink() ?>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
                Финансы команды:
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <span class="strong">
                    <?= FormatHelper::asCurrency($model->team->finance) ?>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
                Начальная цена за 1 день аренды:
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <span class="strong">
                    <?= FormatHelper::asCurrency($model->minPrice) ?>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
                Срок аренды:
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <span class="strong"><?= $model->minDay ?>-<?= $model->maxDay ?></span> дней
            </div>
        </div>
        <?php $form = ActiveForm::begin([
            'enableAjaxValidation' => true,
            'fieldConfig' => [
                'errorOptions' => [
                    'class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center notification-error',
                    'tag' => 'div',
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
        <?= $form->field($model, 'day', [
            'template' => '
                <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">{label}</div>
                <div class="col-lg-1 col-md-2 col-sm-2 col-xs-6">{input}</div>
                </div>
                <div class="row">{error}</div>'
        ])->textInput() ?>
        <div class="row">
            <?= $form->field($model, 'onlyOne', [
                'template' => '
                <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">{input}</div>
                </div>
                <div class="row">{error}</div>'
            ])->checkbox() ?>
        </div>
        <p class="text-center">
            <?php if ($model->loanApplication) : ?>
                <?= Html::submitButton('Редактировать заявку', ['class' => 'btn']) ?>
                <?= Html::a(
                    'Удалить заявку',
                    'javascript:',
                    ['class' => 'btn', 'id' => 'btn' . $modelFromClassName]
                ) ?>
            <?php else: ?>
                <?= Html::submitButton('Подать заявку', ['class' => 'btn']) ?>
            <?php endif ?>
        </p>
        <?php $form::end() ?>
        <?php $form = ActiveForm::begin([
            'enableAjaxValidation' => true,
            'options' => [
                'id' => 'form' . $modelFromClassName,
                'style' => [
                    'display' => 'none',
                ],
            ],
        ]) ?>
        <?= $form->field($modelFrom, 'off')->hiddenInput(['value' => true])->label(false) ?>
        <?php $form::end() ?>
    </div>
</div>
