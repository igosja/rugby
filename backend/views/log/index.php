<?php

// TODO refactor

/**
 * @var ArrayDataProvider $dataProvider
 */

use common\components\helpers\ErrorHelper;
use common\models\db\Log;
use yii\data\ArrayDataProvider;
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
            <?= Html::a('Clear', ['clear'], ['class' => 'btn btn-default']) ?>
        </li>
    </ul>
    <div class="row">
        <div class="col-lg-12">
            <?php

            try {
                $columns = [
                    [
                        'attribute' => 'id',
                        'headerOptions' => ['class' => 'col-lg-1'],
                    ],
                    [
                        'attribute' => 'log_time',
                        'format' => 'datetime',
                        'headerOptions' => ['class' => 'col-lg-3'],
                    ],
                    'level',
                    'category',
                    'prefix',
                    [
                        'attribute' => 'message',
                        'format' => 'raw',
                        'value' => static function (Log $model) {
                            return nl2br($model->message);
                        },
                    ],
                ];
                print GridView::widget([
                    'columns' => $columns,
                    'dataProvider' => $dataProvider,
                ]);
            } catch (Exception $e) {
                ErrorHelper::log($e);
            }

            ?>
        </div>
    </div>
</div>
