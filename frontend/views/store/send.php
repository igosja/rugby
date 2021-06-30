<?php

// TODO refactor

use common\models\db\User;
use frontend\models\forms\UserTransferMoney;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var UserTransferMoney $model
 * @var User $user
 * @var array $userArray
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h1><?= Yii::t('frontend', 'views.store.h1') ?></h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong">
        <p class="text-center">
            <?= Yii::t('frontend', 'views.store.p.1', ['value' => Yii::$app->formatter->asDecimal($user->money, 2)]) ?>
        </p>
    </div>
</div>
<div class="row margin-top-small text-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//store/_links') ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p>
            <?= Yii::t('frontend', 'views.store.send.p.1') ?>
        </p>
        <p>
            <?= Yii::t('frontend', 'views.store.send.p.2') ?>
        </p>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-center"><?= Yii::t('frontend', 'views.store.send.p.3') ?></p>
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
            ->field($model, 'userId')
            ->dropDownList($userArray, ['class' => 'form-control', 'prompt' => Yii::t('frontend', 'views.store.send.prompt.user')])
            ->label(Yii::t('frontend', 'views.store.send.label.user')) ?>
        <div class="row">
            <div class="col-lg-5 col-md-4 col-sm-4 col-xs-12 text-right xs-text-center">
                <?= Yii::t('frontend', 'views.store.send.label.available') ?>
            </div>
            <div class="col-lg-3 col-md-5 col-sm-5 col-xs-12">
                <span class="strong"><?= Yii::$app->formatter->asDecimal($user->money, 2) ?></span>
            </div>
        </div>
        <?= $form
            ->field($model, 'sum')
            ->textInput(['class' => 'form-control', 'type' => 'number', 'step' => 0.01])
            ->label(Yii::t('frontend', 'views.store.send.label.sum')) ?>
        <p class="text-center">
            <?= Html::submitButton(Yii::t('frontend', 'views.store.send.submit'), ['class' => 'btn margin']) ?>
        </p>
        <?php ActiveForm::end() ?>
    </div>
</div>
