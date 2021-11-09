<?php

// TODO refactor

/**
 * @var ArrayDataProvider $dataProvider
 * @var \backend\models\search\LogSearch $searchModel
 */

use common\components\helpers\ErrorHelper;
use common\models\db\Log;
use yii\data\ArrayDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

?>

<div class="site-index">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h1 class="page-header"><?= Html::encode($this->title) ?></h1>
        </div>
    </div>
    <ul class="list-inline preview-links text-center">
        <li>
            <?= Html::a(
                'Clear',
                ['clear'],
                [
                    'class' => 'btn btn-default',
                    'data' => [
                        'confirm' => 'Are you sure?',
                    ],
                ]
            ) ?>
        </li>
    </ul>
    <div class="row">
        <div class="col-lg-12">
            <?php

            try {
                $columns = [
                    [
                        'attribute' => 'log_time',
                        'format' => 'datetime',
                        'headerOptions' => ['class' => 'col-lg-1'],
                    ],
                    [
                        'attribute' => 'message',
                        'format' => 'raw',
                        'value' => static function (Log $model) {
                            return nl2br($model->message);
                        },
                    ],
                    [
                        'class' => ActionColumn::class,
                        'contentOptions' => ['class' => 'text-center'],
                        'headerOptions' => ['class' => 'col-lg-1'],
                        'template' => '{delete}',
                    ],
                ];
                print GridView::widget([
                    'columns' => $columns,
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                ]);
            } catch (Exception $e) {
                ErrorHelper::log($e);
            }

            ?>
        </div>
    </div>
</div>
