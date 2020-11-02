<?php

use backend\models\forms\SignInForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var $model SignInForm
 */

?>
<div class="row">
    <div class="col-lg-4 col-md-8 col-sm-10 col-xs-12 col-lg-offset-4 col-md-offset-2 col-sm-offset-1">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center">Sign In</h3>
            </div>
            <div class="panel-body">
                <?php
                $form = ActiveForm::begin(
                    [
                        'fieldConfig' => [
                            'template' => '{input}{error}'
                        ]
                    ]
                ) ?>
                <fieldset>
                    <?= $form->field($model, 'login')->textInput(
                        [
                            'autofocus' => true,
                            'placeholder' => $model->getAttributeLabel('login')
                        ]
                    ) ?>
                    <?= $form->field($model, 'password')->passwordInput(
                        [
                            'placeholder' => $model->getAttributeLabel('password')
                        ]
                    ) ?>
                    <?= Html::submitButton(
                        'Sign In',
                        ['class' => 'btn btn-lg btn-primary btn-block']
                    ) ?>
                </fieldset>
                <?php
                ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>
