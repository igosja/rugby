<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Game;
use common\models\db\Team;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var ActiveDataProvider $dataProvider
 * @var array $seasonArray
 * @var integer $seasonId
 * @var Team $team
 * @var View $this
 * @var array $totalGameResult
 * @var integer $totalPoint
 */

?>
<div class="row margin-top">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?= $this->render('//team/_team-top-left', ['team' => $team]) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
        <?= $this->render('//team/_team-top-right', ['team' => $team]) ?>
    </div>
</div>
<?= Html::beginForm(['team/game', 'id' => $team->team_id], 'get') ?>
<div class="row margin-top-small">
    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
        <?= $this->render('//team/_team-links', ['id' => $team->team_id]) ?>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                <?= Html::label('Сезон', 'seasonId') ?>
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
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table class="table table-bordered table-hover">
            <tr>
                <th>Итоги сезона</th>
                <th class="col-10" title="Матчи">М</th>
                <th class="col-10 hidden-xs" title="Победы">В</th>
                <th class="col-10 hidden-xs" title="Победы в овертайме">ВО</th>
                <th class="col-10 hidden-xs" title="Ничьи и победы/поражения по буллитам">Н</th>
                <th class="col-10 hidden-xs" title="Поражения в овертайме">ПО</th>
                <th class="col-10 hidden-xs" title="Поражения">П</th>
            </tr>
            <tr>
                <td>Всего сыграно матчей</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
            </tr>
        </table>
    </div>
</div>
<div class="row margin-top">
    <?php

// TODO refactor

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Дата',
                'headerOptions' => ['class' => 'col-15'],
                'label' => 'Дата',
                'value' => static function (Game $model): string {
                    return FormatHelper::asDate($model->schedule->schedule_date);
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => 'Турнир',
                'footerOptions' => ['class' => 'hidden-xs'],
                'headerOptions' => ['class' => 'col-30 hidden-xs'],
                'label' => 'Турнир',
                'value' => static function (Game $model): string {
                    return $model->schedule->tournamentType->tournament_type_name;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => 'Стадия',
                'footerOptions' => ['class' => 'hidden-xs'],
                'headerOptions' => ['class' => 'col-10 hidden-xs'],
                'label' => 'Стадия',
                'value' => static function (Game $model): string {
                    return $model->schedule->stage->stage_name;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Дома/В гостях'],
                'headerOptions' => ['class' => 'col-1 hidden-xs', 'title' => 'Дома/В гостях'],
                'value' => static function (Game $model) use ($team): string {
                    return $model->gameHomeGuest($team->team_id);
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => 'С/С',
                'footerOptions' => [
                    'class' => 'hidden-xs',
                    'title' => 'Соотношение сил (чем больше это число, тем сильнее соперник)'
                ],
                'headerOptions' => [
                    'class' => 'col-5 hidden-xs',
                    'title' => 'Соотношение сил (чем больше это число, тем сильнее соперник)'
                ],
                'label' => 'С/С',
                'value' => static function (Game $model) use ($team): string {
                    return $model->gamePowerPercent($team->team_id);
                }
            ],
            [
                'footer' => 'Соперник',
                'format' => 'raw',
                'label' => 'Соперник',
                'value' => static function (Game $model) use ($team): string {
                    return $model->opponentLink($team->team_id);
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => 'А',
                'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Автосостав'],
                'headerOptions' => ['class' => 'col-1 hidden-xs', 'title' => 'Автосостав'],
                'label' => 'А',
                'value' => static function (Game $model) use ($team): string {
                    return $model->gameAuto($team->team_id);
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => 'Счёт',
                'format' => 'raw',
                'label' => 'Счёт',
                'value' => static function (Game $model) use ($team): string {
                    return Html::a(
                        $model->formatTeamScore($team->team_id),
                        ['game/view', 'id' => $model->game_id]
                    );
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footer' => 0,
                'footerOptions' => ['class' => 'hidden-xs', 'title' => 'Количество набранных/потерянных баллов'],
                'headerOptions' => ['class' => 'col-1 hidden-xs', 'title' => 'Количество набранных/потерянных баллов'],
                'value' => static function (Game $model) use ($team): string {
                    return $model->gamePlusMinus($team->team_id);
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
        <?= $this->render('//team/_team-links', ['id' => $team->team_id]) ?>
    </div>
</div>
<?= $this->render('//site/_show-full-table') ?>
