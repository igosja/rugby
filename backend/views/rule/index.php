<?php

// TODO refactor

use backend\models\search\RuleSearch;
use common\components\helpers\ErrorHelper;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var ActiveDataProvider $dataProvider
 * @var RuleSearch $searchModel
 * @var View $this
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <h3 class="page-header"><?= Html::encode($this->title) ?></h3>
    </div>
</div>
<ul class="list-inline preview-links text-center">
    <li>
        <?= Html::a('Create', ['create'], ['class' => 'btn btn-default']) ?>
    </li>
</ul>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'attribute' => 'id',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'col-lg-1 text-center'],
            ],
            [
                'attribute' => 'date',
                'format' => 'datetime',
                'headerOptions' => ['class' => 'col-lg-3 text-center'],
            ],
            [
                'attribute' => 'title',
                'headerOptions' => ['class' => 'col-lg-6 text-center'],
            ],
            [
                'attribute' => 'order',
                'contentOptions' => ['class' => 'text-center'],
                'format' => 'integer',
                'headerOptions' => ['class' => 'col-lg-1 text-center'],
            ],
            [
                'class' => ActionColumn::class,
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'col-lg-1'],
            ],
        ];
        print GridView::widget(
            [
                'columns' => $columns,
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
            ]
        );
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>