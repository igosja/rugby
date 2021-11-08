<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var \common\models\db\User $model
 * @var array $userArray
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php $form = ActiveForm::begin() ?>
        <?= $form->field($model, 'login')->textInput() ?>
        <?php

        try {
            print $form
                ->field($model, 'referrer_user_id')
                ->widget(Select2::class, ['data' => $userArray]);
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        ?>
        <div class="form-group">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <?= Html::submitButton('Save', ['class' => 'btn btn-default']) ?>
            </div>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
