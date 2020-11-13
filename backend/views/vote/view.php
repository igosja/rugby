<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\models\db\Vote;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/**
 * @var ActiveDataProvider $dataProvider
 * @var Vote $model
 * @var View $this
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <h3 class="page-header"><?= Html::encode($this->title) ?></h3>
    </div>
</div>
<ul class="list-inline preview-links text-center">
    <?php if (VoteStatus::NEW === $model->vote_status_id) : ?>
        <li>
            <?= Html::a('Approve', ['approve', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
        </li>
    <?php endif; ?>
    <li>
        <?= Html::a('List', ['index'], ['class' => 'btn btn-default']) ?>
    </li>
    <li>
        <?= Html::a('Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </li>
    <li>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </li>
</ul>
<div class="row">
    <?php

// TODO refactor

    try {
        $attributes = [
            'id',
            'date:datetime',
            [
                'attribute' => 'federation_id',
                'value' => static function (Vote $model) {
                    return $model->federation ? $model->federation->country->name : '-';
                },
            ],
            [
                'attribute' => 'user_id',
                'format' => 'raw',
                'value' => static function (Vote $model) {
                    return $model->user->getUserLink();
                },
            ],
            'text',
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
        <strong>Answer options:</strong>
    </div>
</div>
<div class="row">
    <?php

// TODO refactor

    try {
        $columns = [
            [
                'attribute' => 'text',
            ]
        ];
        print GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => $columns,
            'showHeader' => false,
            'summary' => false,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
