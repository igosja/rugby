<?php

use common\models\db\User;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var User $model
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php
        $form = ActiveForm::begin() ?>
        <?= $form->field($model, 'login')->textInput() ?>
        <div class="form-group">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <?= Html::submitButton('Save', ['class' => 'btn btn-default']) ?>
            </div>
        </div>
        <?php
        ActiveForm::end() ?>
    </div>
</div>
