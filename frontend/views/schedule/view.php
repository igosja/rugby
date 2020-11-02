<?php

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Game;
use common\models\db\Schedule;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var ActiveDataProvider $dataProvider
 * @var Schedule $schedule
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h1><?= $schedule->tournamentType->tournament_type_name ?></h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <p>
            <?= FormatHelper::asDatetime($schedule->schedule_date) ?>,
            <?= $schedule->stage->stage_name ?>,
            <?= $schedule->schedule_season_id ?>
            сезон
        </p>
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'col-47 text-right'],
                'format' => 'raw',
                'value' => function (Game $model) {
                    return $model->teamOrNationalLink() . $model->formatAuto();
                }
            ],
            [
                'contentOptions' => ['class' => 'col-6 text-center'],
                'format' => 'raw',
                'value' => function (Game $model) {
                    return Html::a(
                        $model->formatScore(),
                        ['game/view', 'id' => $model->game_id]
                    );
                }
            ],
            [
                'contentOptions' => ['class' => 'col-47'],
                'format' => 'raw',
                'value' => function (Game $model) {
                    return $model->teamOrNationalLink('guest') . $model->formatAuto('guest');
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $dataProvider,
            'showHeader' => false,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
