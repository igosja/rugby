<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\models\db\Chat;
use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var \common\models\db\Chat $model
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
        <?= Html::a(
            'Approve',
            ['moderation/chat-ok', 'id' => $model->id],
            ['class' => 'btn btn-default']
        ) ?>
    </li>
    <li>
        <?= Html::a(
            'Edit',
            ['moderation/chat-update', 'id' => $model->id],
            ['class' => 'btn btn-default']
        ) ?>
    </li>
    <li>
        <?= Html::a(
            'Delete',
            ['moderation/chat-delete', 'id' => $model->id],
            ['class' => 'btn btn-default']
        ) ?>
    </li>
</ul>
<div class="row">
    <?php

    try {
        $attributes = [
            [
                'captionOptions' => ['class' => 'col-lg-6'],
                'format' => 'raw',
                'label' => 'User',
                'value' => static function (Chat $model) {
                    return $model->user->getUserLink();
                },
            ],
        ];
        print DetailView::widget([
            'attributes' => $attributes,
            'model' => $model,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= nl2br($model->message) ?>
    </div>
</div>
