<?php

// TODO refactor

/**
 * @var ActiveForm $form
 * @var ForgotPasswordForm $forgotPasswordForm
 * @var View $this
 */

use frontend\models\forms\ForgotPasswordForm;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            <h1>Забыли пароль?</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            <?= $this->render('_sign-up-links') ?>
        </div>
    </div>
<?php

// TODO refactor
$form = ActiveForm::begin(
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
                Здесь вы можете запросить отправку <strong>забытого пароля на свой почтовый ящик</strong>,
                который был указан вами при регистрации.
            </p>
            <p>
                Укажите ваш <strong>логин</strong> или <strong>email</strong>:
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?= $form->field($forgotPasswordForm, 'login')->textInput(['autoFocus' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?= $form->field($forgotPasswordForm, 'email')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            <?= Html::submitButton('Восстановить пароль', ['class' => 'btn margin']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            <p>
                Если при регистрации вы ввели свой email неправильно или он уже не работает,
                <br/>
                то напишите нам письмо на <span class="strong"><?= Yii::$app->params['infoEmail'] ?></span>
                <br/>
                и мы попробуем найти ваш аккаунт вручную.
            </p>
        </div>
    </div>
<?php

// TODO refactor
ActiveForm::end() ?>