<?php

// TODO refactor

/**
 * @var SignUpForm $model
 * @var View $this
 */

use frontend\models\forms\SignUpForm;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <h1><?= Yii::t('frontend', 'views.site.sign-up.h1') ?></h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= $this->render('_sign-up-links') ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
    </div>
</div>
<?php $form = ActiveForm::begin([
    'id' => 'sign-up-form',
    'enableAjaxValidation' => true,
    'fieldConfig' => [
        'errorOptions' => ['class' => 'col-lg-4 col-md-3 col-sm-3 col-xs-12 xs-text-center notification-error'],
        'labelOptions' => ['class' => 'strong'],
        'options' => ['class' => 'row'],
        'template' => '<div class="col-lg-5 col-md-4 col-sm-4 col-xs-12 text-right xs-text-center">{label}</div>
            <div class="col-lg-3 col-md-5 col-sm-5 col-xs-12">{input}</div>
            {error}',
    ],
]) ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <p>
            <?= Yii::t('frontend', 'views.site.sign-up.p.1') ?>:
        </p
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $form->field($model, 'login')->textInput(['autoFocus' => true]) ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <p>
            <?= Yii::t('frontend', 'views.site.sign-up.p.2') ?>:
        </p>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $form->field($model, 'email')->textInput() ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::submitButton(Yii::t('frontend', 'views.site.sign-up.submit'), ['class' => 'btn margin']) ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <p>
            <?= Yii::t('frontend', 'views.site.sign-up.p.3') ?>
        </p>
        <p>
            <?= Yii::t('frontend', 'views.site.sign-up.p.4') ?>
        </p>
    </div>
</div>
<?php ActiveForm::end() ?>
