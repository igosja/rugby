<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\models\db\TeamRequest;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
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
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'col-lg-1 text-center'],
            ],
            [
                'attribute' => 'date',
                'format' => 'datetime',
                'headerOptions' => ['class' => 'col-lg-2 text-center'],
            ],
            [
                'attribute' => 'leave_team_id',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-lg-3 text-center'],
                'value' => static function (TeamRequest $model) {
                    if (!$model->leave_team_id) {
                        return '-';
                    }
                    return $model->leaveTeam->getTeamLink();
                }
            ],
            [
                'attribute' => 'team_id',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-lg-3 text-center'],
                'value' => static function (TeamRequest $model) {
                    return $model->team->getTeamLink();
                }
            ],
            [
                'attribute' => 'user_id',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-lg-2 text-center'],
                'value' => static function (TeamRequest $model) {
                    return $model->user->getUserLink();
                }
            ],
            [
                'class' => ActionColumn::class,
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'col-lg-1 text-center'],
                'template' => '{view}',
            ],
        ];
        print GridView::widget(
            [
                'columns' => $columns,
                'dataProvider' => $dataProvider,
            ]
        );
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>