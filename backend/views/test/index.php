<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var ActiveDataProvider $dataProvider
 * @var View $this
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <h3 class="page-header"><?= Html::encode($this->title) ?></h3>
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'attribute' => 'id',
                'headerOptions' => ['class' => 'col-lg-1'],
            ],
            [
                'attribute' => 'value',
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