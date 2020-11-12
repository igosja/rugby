<?php

// TODO refactor

/**
 * @var ActiveForm $form
 * @var ActivationForm $activationForm
 * @var View $this
 */

use frontend\models\forms\ActivationForm;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            <h1>Активация аккаунта</h1>
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
                <strong>Активировать свой аккаунт</strong> - это значит подтвердить,
                что указанный при регистрации почтовый ящик принадлежит вам и работает.<br/>
                Только после этого вы сможете полностью пользоваться функциями сайта.
            </p>
            <p>
                Для активации своего аккаунта вам нужно ввести код активации,
                который был отправлен вам по электронной почте.
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?= $form->field($activationForm, 'code')->textInput(['autoFocus' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right xs-text-center">
            <?= Html::submitButton('Активировать аккаунт', ['class' => 'btn margin']) ?>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-left xs-text-center">
            <?= Html::a('Мне не пришло письмо', ['activation-repeat'], ['class' => 'btn margin']) ?>
        </div>
    </div>
<?php

// TODO refactor
ActiveForm::end() ?>