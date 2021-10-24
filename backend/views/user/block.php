<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var array $userBlockReasonArray
 * @var array $userBlockTypeArray
 * @var \common\models\db\UserBlock $model
 * @var \yii\web\View $this
 * @var \common\models\db\User $user
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <h3 class="page-header"><?= Html::encode($this->title) ?></h3>
    </div>
</div>
<ul class="list-inline preview-links text-center">
    <li>
        <?= Html::a('List', ['index'], ['class' => 'btn btn-default']) ?>
    </li>
    <li>
        <?= Html::a('View', ['view', 'id' => $model->user_id], ['class' => 'btn btn-default']) ?>
    </li>
</ul>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php $form = ActiveForm::begin() ?>
        <?php

        try {
            print $form
                ->field($model, 'user_block_type_id')
                ->widget(Select2::class, ['data' => $userBlockTypeArray]);
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        ?>
        <?php

        try {
            print $form
                ->field($model, 'user_block_reason_id')
                ->widget(Select2::class, ['data' => $userBlockReasonArray]);
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
