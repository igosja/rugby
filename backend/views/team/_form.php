<?php

// TODO refactor

use common\models\db\Team;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var Team $model
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php

// TODO refactor
        $form = ActiveForm::begin() ?>
        <?= $form->field($model, 'name')->textInput() ?>
        <div class="form-group">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <?= Html::submitButton('Save', ['class' => 'btn btn-default']) ?>
            </div>
        </div>
        <?php

// TODO refactor
        ActiveForm::end() ?>
    </div>
</div>
