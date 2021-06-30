<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use frontend\models\forms\UserTransferFinance;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var array $federationArray
 * @var UserTransferFinance $model
 * @var array $teamArray
 */

print $this->render('_top');

?>
<div class="row margin-top-small">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//user/_user-links') ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table class="table">
            <tr>
                <th><?= Yii::t('frontend', 'views.user.money-transfer.th') ?></th>
            </tr>
        </table>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-center">
            <?= Yii::t('frontend', 'views.user.money-transfer.p.1') ?>
        </p>
        <p class="text-center">
            <?= Yii::t('frontend', 'views.user.money-transfer.p.2') ?>
        </p>
        <p class="text-center"><?= Yii::t('frontend', 'views.user.money-transfer.p.3') ?>:</p>
        <?php $form = ActiveForm::begin([
            'fieldConfig' => [
                'errorOptions' => [
                    'class' => 'col-lg-4 col-md-3 col-sm-3 col-xs-12 xs-text-center notification-error',
                    'tag' => 'div'
                ],
                'options' => ['class' => 'row'],
                'template' =>
                    '<div class="col-lg-5 col-md-4 col-sm-4 col-xs-12 text-right xs-text-center">{label}</div>
                    <div class="col-lg-3 col-md-5 col-sm-5 col-xs-12">{input}</div>
                    {error}',
            ],
        ]) ?>
        <?= $form
            ->field($model, 'teamId')
            ->dropDownList($teamArray, ['class' => 'form-control', 'prompt' => Yii::t('frontend', 'views.user.money-transfer.prompt.team')])
            ->label(Yii::t('frontend', 'views.user.money-transfer.label.team')) ?>
        <div class="row">
            <div class="col-lg-5 col-md-4 col-sm-4 col-xs-12 text-right xs-text-center">
                <?= Yii::t('frontend', 'views.user.money-transfer.available') ?>
            </div>
            <div class="col-lg-3 col-md-5 col-sm-5 col-xs-12">
                <span class="strong"><?= FormatHelper::asCurrency($model->user->finance) ?></span>
            </div>
        </div>
        <?= $form
            ->field($model, 'sum')
            ->textInput(['class' => 'form-control', 'type' => 'number'])
            ->label(Yii::t('frontend', 'views.user.money-transfer.label.sum')) ?>
        <?= $form
            ->field($model, 'comment')
            ->textarea(['class' => 'form-control'])
            ->label(Yii::t('frontend', 'views.user.money-transfer.label.comment')) ?>
        <p class="text-center">
            <?= Html::submitButton(Yii::t('frontend', 'views.user.money-transfer.submit'), ['class' => 'btn margin']) ?>
        </p>
        <?php ActiveForm::end() ?>
    </div>
</div>
