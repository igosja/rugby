<?php

// TODO refactor

use backend\models\search\VoteSearch;
use common\components\helpers\ErrorHelper;
use common\models\db\Vote;
use common\models\db\VoteStatus;
use rmrevin\yii\fontawesome\FAS;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var ActiveDataProvider $dataProvider
 * @var VoteSearch $searchModel
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
                'attribute' => 'text',
                'format' => 'raw',
                'headerOptions' => ['class' => 'text-center'],
                'value' => static function (Vote $model): string {
                    $result = '';
                    if (VoteStatus::NEW === $model->vote_status_id) {
                        $result .= FAS::icon(FAS::_CLOCK) . ' ';
                    }
                    $result .= $model->text;
                    return $result;
                }
            ],
            [
                'class' => ActionColumn::class,
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'col-lg-1'],
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
