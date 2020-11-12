<?php

// TODO refactor

use common\models\db\Rule;
use yii\helpers\Html;

/**
 * @var Rule $model
 */

?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-top">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?= Html::a($model->title, ['rule/view', 'id' => $model->id]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?= $model->formatSearchText() ?>
        </div>
    </div>
</div>
