<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var \common\models\db\Payment $model
 * @var \yii\web\View $this
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <h3 class="page-header"><?= Html::encode($this->title) ?></h3>
    </div>
</div>
<ul class="list-inline preview-links text-center">
    <li>
        <?= Html::a('List', ['user/index'], ['class' => 'btn btn-default']) ?>
    </li>
    <li>
        <?= Html::a('View', ['user/view', 'id' => $model->user_id], ['class' => 'btn btn-default']) ?>
    </li>
</ul>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php $form = ActiveForm::begin() ?>
        <?= $form->field($model, 'sum')->textInput(['type' => 'number', 'step' => 0.01]) ?>
        <div class="form-group">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <?= Html::submitButton('Add', ['class' => 'btn btn-default']) ?>
            </div>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
