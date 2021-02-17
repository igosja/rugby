<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use common\models\db\Payment;
use common\models\db\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var string $bonusLine
 * @var Payment $model
 * @var User $user
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h1><?= Yii::t('frontend', 'views.store.payment.h1') ?></h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong">
        <p class="text-center">
            <?= Yii::t('frontend', 'views.store.p.1', ['value' => Yii::$app->formatter->asDecimal($user->money, 2)]) ?>
        </p>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= $this->render('//store/_links') ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-justify">
            <?= Yii::t('frontend', 'views.store.payment.p.1', [
                'link' => Html::a(Yii::t('frontend', 'views.store.payment.link.1'), ['index']),
            ]) ?>
        </p>
        <p class="text-justify">
            <?= Yii::t('frontend', 'views.store.payment.p.2', [
                'amount' => FormatHelper::asCurrency(1),
            ]) ?>
        </p>
        <p class="text-justify">
            <?= Yii::t('frontend', 'views.store.payment.p.3', [
                'link' => Html::a(Yii::t('frontend', 'views.store.payment.link.2'), ['index']),
            ]) ?>
        </p>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-center">
            <span class="strong"><?= Yii::t('frontend', 'views.store.payment.bonus') ?></span>:
            <?= $bonusLine ?>
        </p>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-center">
            <?= Yii::t('frontend', 'views.store.payment.big') ?>
        </p>
    </div>
</div>
<?php $form = ActiveForm::begin([
    'fieldConfig' => [
        'errorOptions' => ['class' => 'col-lg-3 col-md-3 col-sm-3 col-xs-12 xs-text-center notification-error', 'tag' => 'div'],
        'labelOptions' => ['class' => 'strong'],
        'options' => ['class' => 'row margin-top'],
        'template' =>
            '<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 text-right xs-text-center">{label}</div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-left">{input}</div>
            {error}',
    ],
]) ?>
<?= $form
    ->field($model, 'sum')
    ->textInput(['class' => 'form-control form-small', 'type' => 'number'])
    ->label(Yii::t('frontend', 'views.store.payment.label.sum')) ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::submitButton(Yii::t('frontend', 'views.store.payment.submit'), ['class' => 'btn margin']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
