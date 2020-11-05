<?php

use common\components\helpers\ErrorHelper;
use frontend\models\forms\SignInForm;
use rmrevin\yii\fontawesome\FAS;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var SignInForm $signInForm
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <h1>Вход</h1>
    </div>
</div>
<?php
$form = ActiveForm::begin(
    [
        'enableAjaxValidation' => true,
        'fieldConfig' => [
            'errorOptions' => [
                'class' => 'col-lg-4 col-md-3 col-sm-3 col-xs-12 xs-text-center notification-error',
                'tag' => 'div'
            ],
            'labelOptions' => ['class' => 'strong'],
            'options' => ['class' => 'row'],
            'template' => '<div class="col-lg-5 col-md-4 col-sm-4 col-xs-12 text-right xs-text-center">{label}</div>
                        <div class="col-lg-3 col-md-5 col-sm-5 col-xs-12">{input}</div>
                        {error}',
        ],
    ]
) ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $form->field($signInForm, 'login')->textInput(['autoFocus' => true]) ?>
        <?= $form->field($signInForm, 'password')->passwordInput() ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::submitButton('Вход', ['class' => 'btn margin']) ?>
    </div>
</div>
<?php
ActiveForm::end() ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        Или войдите с помощью соцсетей:
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?php
        try {
            print Html::a(
                FAS::icon(FAS::_FACEBOOK_SQUARE)->size(FAS::SIZE_2X),
                'javascript:',
                ['title' => 'Facebook']
            );
        } catch (InvalidConfigException $e) {
            ErrorHelper::log($e);
        }
        ?>
        <?php
        try {
            print Html::a(
                FAS::icon(FAS::_GOOGLE_PLUS_SQUARE)->size(FAS::SIZE_2X),
                'javascript:',
                ['title' => 'Google+']
            );
        } catch (InvalidConfigException $e) {
            ErrorHelper::log($e);
        }
        ?>
    </div>
</div>
