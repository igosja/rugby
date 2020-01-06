<?php

use common\components\helpers\ErrorHelper;
use common\models\db\Lineup;
use common\models\db\Player;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var int $assist
 * @var ActiveDataProvider $dataProvider
 * @var Player $player
 * @var array $seasonArray
 * @var int $seasonId
 * @var View $this
 */

print $this->render('//player/_player', ['player' => $player]);

?>
<?= Html::beginForm(['player/view', 'id' => $player->player_id], 'get') ?>
<div class="row margin-top-small">
    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
        <?= $this->render('//player/_links', ['id' => $player->player_id]) ?>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                <?= Html::label('Season', 'seasonId') ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <?= Html::dropDownList(
                    'seasonId',
                    $seasonId,
                    $seasonArray,
                    ['class' => 'form-control submit-on-change', 'id' => 'seasonId']
                ) ?>
            </div>
        </div>
    </div>
</div>
<?= Html::endForm() ?>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Дата',
                'headerOptions' => ['class' => 'col-10'],
                'label' => 'Дата',
                'value' => static function (Lineup $model) {
                    return Yii::$app->formatter->asDate($model->game->schedule->schedule_date, 'short');
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Матч',
                'format' => 'raw',
                'label' => 'Матч',
                'value' => static function (Lineup $model) {
                    return $model->game->teamOrNationalLink('home', false)
                        . '-'
                        . $model->game->teamOrNationalLink('guest', false);
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Сч',
                'footerOptions' => ['title' => 'Счёт'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-5', 'title' => 'Счёт'],
                'label' => 'Сч',
                'value' => static function (Lineup $model) {
                    return Html::a(
                        $model->game->formatScore(),
                        ['game/view', 'id' => $model->game->game_id]
                    );
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Тип матча',
                'headerOptions' => ['class' => 'col-13'],
                'label' => 'Тип матча',
                'value' => static function (Lineup $model) {
                    return $model->game->schedule->tournamentType->tournament_type_name;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Стадия',
                'headerOptions' => ['class' => 'col-10'],
                'label' => 'Стадия',
                'value' => static function (Lineup $model) {
                    return $model->game->schedule->stage->stage_name;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Поз',
                'footerOptions' => ['title' => 'Позиция'],
                'headerOptions' => ['class' => 'col-10', 'title' => 'Позиция'],
                'label' => 'Поз',
                'value' => static function (Lineup $model) {
                    return $model->position->position_name;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => 'С',
                'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Сила'],
                'headerOptions' => ['class' => 'col-5 hidden-xs', 'title' => 'Сила'],
                'label' => 'С',
                'value' => static function (Lineup $model) {
                    return $model->lineup_power_real;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => 'Ш',
                'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Шайбы'],
                'headerOptions' => ['class' => 'col-5 hidden-xs', 'title' => 'Шайбы'],
                'label' => 'Ш',
                'value' => static function (Lineup $model) {
                    return $model->lineup_point;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => '+/-',
                'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Плюс/минус'],
                'headerOptions' => ['class' => 'col-5 hidden-xs', 'title' => 'Плюс/минус'],
                'label' => '+/-',
                'value' => static function (Lineup $model) {
                    return $model->lineup_plus_minus;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Изменение силы'],
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-5 hidden-xs', 'title' => 'Изменение силы'],
                'value' => static function (Lineup $model) {
                    return $model->iconPowerChange();
                }
            ],
        ];
        print GridView::widget([
            'columns' => $columns,
            'dataProvider' => $dataProvider,
            'showFooter' => true,
            'summary' => false,
        ]);
    } catch (Exception $e) {
        ErrorHelper::log($e);
    }

    ?>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $this->render('//player/_links', ['id' => $player->player_id]) ?>
    </div>
</div>
<?= $this->render('//site/_show-full-table') ?>

