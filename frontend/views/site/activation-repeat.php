<?php

// TODO refactor

/**
 * @var ActiveForm $form
 * @var ActivationRepeatForm $activationRepeatForm
 * @var View $this
 */

use frontend\models\forms\ActivationRepeatForm;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <h1><?= Yii::t('frontend', 'views.site.activation.h1') ?></h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= $this->render('_sign-up-links') ?>
    </div>
</div>
<?php $form = ActiveForm::begin(
    [
        'enableAjaxValidation' => true,
        'fieldConfig' => [
            'errorOptions' => ['class' => 'col-lg-4 col-md-3 col-sm-3 col-xs-12 xs-text-center notification-error'],
            'labelOptions' => ['class' => 'strong'],
            'options' => ['class' => 'row'],
            'template' =>
                '<div class="col-lg-5 col-md-4 col-sm-4 col-xs-12 text-right xs-text-center">{label}</div>
            <div class="col-lg-3 col-md-5 col-sm-5 col-xs-12">{input}</div>
            {error}',
        ],
    ]
) ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <p>
            <?= Yii::t('frontend', 'views.site.activation-repeat.p.1') ?>
        </p>
        <p>
            <?= Yii::t('frontend', 'views.site.activation-repeat.p.2') ?>
        </p>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $form->field($activationRepeatForm, 'email')->textInput(['autoFocus' => true]) ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right xs-text-center">
        <?= Html::submitButton(Yii::t('frontend', 'views.site.activation-repeat.submit'), ['class' => 'btn margin']) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-left xs-text-center">
        <?= Html::a(Yii::t('frontend', 'views.site.activation-repeat.link.activation'), ['site/activation'], ['class' => 'btn margin']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
