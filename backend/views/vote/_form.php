<?php

// TODO refactor

use common\models\db\Vote;
use yii\helpers\Html;

/**
 * @var Vote $model
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php

// TODO refactor $form = ActiveForm::begin() ?>
        <?= $form->field($model, 'text')->textarea() ?>
        <?php

// TODO refactor for ($i = 0; $i < 15; $i++) : ?>
            <?= $form->field($model, 'answers[' . $i . ']')->textarea() ?>
        <?php

// TODO refactor endfor ?>
        <div class="form-group">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <?= Html::submitButton('Save', ['class' => 'btn btn-default']) ?>
            </div>
        </div>
        <?php

// TODO refactor ActiveForm::end() ?>
    </div>
</div>
