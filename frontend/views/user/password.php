<?php

// TODO refactor

use frontend\models\forms\ChangePassword;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var ChangePassword $model
 */

print $this->render('//user/_top');

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
                <th><?= Yii::t('frontend', 'views.user.password.th') ?></th>
            </tr>
        </table>
    </div>
</div>
<?php $form = ActiveForm::begin([
    'fieldConfig' => [
        'errorOptions' => [
            'class' => 'col-lg-4 col-md-3 col-sm-3 col-xs-12 xs-text-center notification-error',
            'tag' => 'div'
        ],
        'labelOptions' => ['class' => 'strong'],
        'options' => ['class' => 'row'],
        'template' =>
            '<div class="col-lg-5 col-md-4 col-sm-4 col-xs-12 text-right xs-text-center">{label}</div>
            <div class="col-lg-3 col-md-5 col-sm-5 col-xs-12">{input}</div>
            {error}',
    ],
]) ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Yii::t('frontend', 'views.user.password.p.1') ?>
    </div>
</div>
<?= $form->field($model, 'old', ['enableAjaxValidation' => true])->passwordInput(['class' => 'form-control']) ?>
<?= $form->field($model, 'new')->passwordInput(['class' => 'form-control']) ?>
<?= $form->field($model, 'repeat')->passwordInput(['class' => 'form-control']) ?>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-justify">
        <?= Yii::t('frontend', 'views.user.password.p.2') ?>
    </div>
</div>
<div class="row margin-top-small">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::submitButton(Yii::t('frontend', 'views.user.password.submit'), ['class' => 'btn']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
