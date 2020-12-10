<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Game;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var ActiveDataProvider $dataProvider
 * @var Game $game
 * @var int $draw
 * @var int $loose
 * @var int $pointAgainst
 * @var int $pointFor
 * @var int $win
 */

?>
<?php if ($game->played) : ?>
    <div class="row margin-top">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
            <?= Html::a('Результат', ['view', 'id' => Yii::$app->request->get('id')]) ?>
            |
            <span class="strong">Перед матчем</span>
        </div>
    </div>
<?php endif ?>
<div class="row <?php if (!$game->played) : ?>margin-top<?php endif ?>">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table class="table table-bordered">
            <tr>
                <th>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center">
                        <?= $game->teamOrNationalLink('home', false) ?>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center">
                        <?= $game->teamOrNationalLink('guest', false) ?>
                    </div>
                </th>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center">
        <?php if (file_exists(Yii::getAlias('@webroot') . '/img/team/125/' . $game->homeTeam->id . '.png')) : ?>
            <?= Html::img(
                '/img/team/125/' . $game->homeTeam->id . '.png?v=' . filemtime(Yii::getAlias('@webroot') . '/img/team/125/' . $game->homeTeam->id . '.png'),
                [
                    'alt' => $game->homeTeam->name,
                    'class' => 'team-logo-game',
                    'title' => $game->homeTeam->name,
                ]
            ) ?>
        <?php endif ?>
    </div>
    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 text-center">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <?= FormatHelper::asDatetime($game->schedule->date) ?>,
                <?= $game->tournamentLink() ?>
            </div>
        </div>
        <?php if ($game->stadium) : ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                    <?= Html::a($game->stadium->name, ['team/view', 'id' => $game->stadium->team->id]) ?>
                    (<?= $game->played ? $game->stadium_capacity : $game->stadium->capacity ?>)
                </div>
            </div>
        <?php endif ?>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center">
        <?php if (file_exists(Yii::getAlias('@webroot') . '/img/team/125/' . $game->guestTeam->id . '.png')) : ?>
            <?= Html::img(
                '/img/team/125/' . $game->guestTeam->id . '.png?v=' . filemtime(Yii::getAlias('@webroot') . '/img/team/125/' . $game->guestTeam->id . '.png'),
                [
                    'alt' => $game->guestTeam->name,
                    'class' => 'team-logo-game',
                    'title' => $game->guestTeam->name,
                ]
            ) ?>
        <?php endif ?>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-center">
                Прогноз на матч
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 table-responsive">
                <?php if ($game->homeTeam->id) : ?>
                    <table class="table table-bordered">
                        <tr>
                            <td
                                    class="text-center
                                <?php if ($game->homeTeam->power_vs > $game->guestTeam->power_vs) : ?>
                                    font-green
                                <?php elseif ($game->homeTeam->power_vs < $game->guestTeam->power_vs) : ?>
                                    font-red
                                <?php endif ?>"
                            >
                                <?= $game->homeTeam->power_vs ?>
                            </td>
                            <td
                                    class="text-center
                                <?php if ($game->homeTeam->power_vs < $game->guestTeam->power_vs) : ?>
                                    font-green
                                <?php elseif ($game->homeTeam->power_vs > $game->guestTeam->power_vs) : ?>
                                    font-red
                                <?php endif ?>"
                            >
                                <?= $game->guestTeam->power_vs ?>
                            </td>
                        </tr>
                    </table>
                <?php else: ?>
                    <table class="table table-bordered">
                        <tr>
                            <td
                                    class="text-center
                                <?php if ($game->homeNational->power_vs > $game->guestNational->power_vs) : ?>
                                    font-green
                                <?php elseif ($game->homeNational->power_vs < $game->guestNational->power_vs) : ?>
                                    font-red
                                <?php endif ?>"
                            >
                                <?= $game->homeNational->power_vs ?>
                            </td>
                            <td
                                    class="text-center
                                <?php if ($game->homeNational->power_vs < $game->guestNational->power_vs) : ?>
                                    font-green
                                <?php elseif ($game->homeNational->power_vs > $game->guestNational->power_vs) : ?>
                                    font-red
                                <?php endif ?>"
                            >
                                <?= $game->guestNational->power_vs ?>
                            </td>
                        </tr>
                    </table>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <?php

    try {
        $columns = [
            [
                'contentOptions' => ['class' => 'text-center'],
                'footerOptions' => ['class' => 'hidden'],
                'header' => 'Дата',
                'headerOptions' => ['class' => 'col-15'],
                'value' => static function (Game $model) {
                    return FormatHelper::asDate($model->schedule->date);
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footerOptions' => ['class' => 'hidden'],
                'header' => 'Турнир',
                'headerOptions' => ['class' => 'hidden-xs'],
                'value' => static function (Game $model) {
                    return $model->schedule->tournamentType->name;
                }
            ],
            [
                'contentOptions' => ['class' => 'hidden-xs text-center'],
                'footerOptions' => ['class' => 'hidden'],
                'header' => 'Стадия',
                'headerOptions' => ['class' => 'col-15 hidden-xs'],
                'value' => static function (Game $model) {
                    return $model->schedule->stage->name;
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footerOptions' => ['class' => 'hidden'],
                'format' => 'raw',
                'header' => 'Игра',
                'value' => static function (Game $model) {
                    return $model->teamOrNationalLink('home', false)
                        . ' - '
                        . $model->teamOrNationalLink('guest', false);
                }
            ],
            [
                'contentOptions' => ['class' => 'text-center'],
                'footer' => '+' . $win . ' =' . $draw . ' -' . $loose . ' (' . $pointFor . ':' . $pointAgainst . ')',
                'footerOptions' => ['colspan' => '5'],
                'format' => 'raw',
                'header' => 'Счёт',
                'headerOptions' => ['class' => 'col-10'],
                'value' => static function (Game $model) {
                    return Html::a(
                        $model->formatScore('home'),
                        ['view', 'id' => $model->id]
                    );
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
<?= $this->render('//site/_show-full-table') ?>
