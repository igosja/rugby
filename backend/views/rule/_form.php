<?php

use common\models\db\Rule;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var Rule $model
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php
        $form = ActiveForm::begin() ?>
        <?= $form->field($model, 'title')->textInput() ?>
        <?= $form->field($model, 'text')->textarea(['rows' => 10]) ?>
        <?= $form->field($model, 'order')->textInput(['type' => 'number']) ?>
        <div class="form-group">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <?= Html::submitButton('Save', ['class' => 'btn btn-default']) ?>
            </div>
        </div>
        <?php
        ActiveForm::end(); ?>
    </div>
</div>