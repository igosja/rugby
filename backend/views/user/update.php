<?php

// TODO refactor

use yii\helpers\Html;

/**
 * @var \common\models\db\User $model
 * @var \yii\web\View $this
 * @var array $userArray
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
        <?= Html::a('View', ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </li>
</ul>
<?= $this->render('_form', ['model' => $model, 'userArray' => $userArray]) ?>
