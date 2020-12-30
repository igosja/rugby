<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Game;
use common\models\db\TournamentType;
use miloschuman\highcharts\Highcharts;
use yii\web\View;

/**
 * @var Game $game
 * @var array $sDataIncome
 * @var array $sDataVisitor
 * @var int $special
 * @var View $this
 * @var array $xData
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h1>
            Прогноз посещаемости
        </h1>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table class="table table-bordered table-hover">
            <tr>
                <th colspan="2">
                    <?= $game->teamOrNationalLink() ?>
                    -
                    <?= $game->teamOrNationalLink('guest') ?>
                </th>
            </tr>
            <tr>
                <td class="col-50">
                    Сезон
                </td>
                <td class="text-right">
                    <?= $game->schedule->season_id ?>
                </td>
            </tr>
            <tr>
                <td>
                    Дата
                </td>
                <td class="text-right">
                    <?= FormatHelper::asDate($game->schedule->date) ?>
                </td>
            </tr>
            <tr>
                <td>
                    Тип турнира
                </td>
                <td class="text-right">
                    <?= $game->schedule->tournamentType->name ?>
                </td>
            </tr>
            <tr>
                <td>
                    Коэффициент типа турнира
                </td>
                <td class="text-right">
                    <?= $game->schedule->tournamentType->visitor / 100 ?>
                </td>
            </tr>
            <?php if (TournamentType::CHAMPIONSHIP === $game->schedule->tournament_type_id) : ?>
                <tr>
                    <td>
                        Дивизион
                    </td>
                    <td class="text-right">
                        <?= $game->homeTeam->championship->division->name ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Коэффициент дивизиона
                    </td>
                    <td class="text-right">
                        <?= Yii::$app->formatter->asDecimal((100 - ($game->homeTeam->championship->division_id - 1)) / 100) ?>
                    </td>
                </tr>
            <?php endif ?>
            <tr>
                <td>
                    Стадия
                </td>
                <td class="text-right">
                    <?= $game->schedule->stage->name ?>
                </td>
            </tr>
            <tr>
                <td>
                    Коэффициент стадии турнира
                </td>
                <td class="text-right">
                    <?= $game->schedule->stage->visitor / 100 ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?= $game->stadium->name ?> (вместимость)
                </td>
                <td class="text-right">
                    <?= $game->stadium->capacity ?>
                </td>
            </tr>
            <tr>
                <td>
                    Рейтинг посещаемости хозяев
                </td>
                <td class="text-right">
                    <?= Yii::$app->formatter->asDecimal($game->homeTeam->visitor / 100) ?>
                </td>
            </tr>
            <tr>
                <td>
                    Рейтинг посещаемости гостей
                </td>
                <td class="text-right">
                    <?= Yii::$app->formatter->asDecimal($game->guestTeam->visitor / 100) ?>
                </td>
            </tr>
            <tr>
                <td>
                    Количество кумиров в заявке на матч
                </td>
                <td class="text-right">
                    <?= $special ?>
                </td>
            </tr>
        </table>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <?php

                try {
                    print Highcharts::widget([
                        'options' => [
                            'credits' => [
                                'enabled' => false,
                            ],
                            'legend' => [
                                'enabled' => false,
                            ],
                            'series' => [
                                [
                                    'name' => 'Посещаемость',
                                    'data' => $sDataVisitor,
                                ]
                            ],
                            'title' => [
                                'text' => 'Посещаемость матча',
                            ],
                            'tooltip' => [
                                'headerFormat' => 'Цена билета <b>{point.key}</b><br/>',
                                'pointFormat' => '{series.name} <b>{point.y}</b>',
                            ],
                            'xAxis' => [
                                'categories' => $xData,
                                'title' => [
                                    'text' => 'Цена билета',
                                ],
                            ],
                            'yAxis' => [
                                'title' => [
                                    'text' => 'Посещаемость',
                                ],
                            ],
                        ]
                    ]);
                } catch (Exception $e) {
                    ErrorHelper::log($e);
                }

                ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <?php

                try {
                    print Highcharts::widget([
                        'options' => [
                            'credits' => [
                                'enabled' => false,
                            ],
                            'legend' => [
                                'enabled' => false,
                            ],
                            'series' => [
                                [
                                    'name' => 'Выручка',
                                    'data' => $sDataIncome,
                                ]
                            ],
                            'title' => [
                                'text' => 'Выручка за билеты',
                            ],
                            'tooltip' => [
                                'headerFormat' => 'Цена билета <b>{point.key}</b><br/>',
                                'pointFormat' => '{series.name} <b>{point.y}</b>',
                            ],
                            'xAxis' => [
                                'categories' => $xData,
                                'title' => [
                                    'text' => 'Цена билета',
                                ],
                            ],
                            'yAxis' => [
                                'title' => [
                                    'text' => 'Выручка',
                                ],
                            ],
                        ]
                    ]);
                } catch (Exception $e) {
                    ErrorHelper::log($e);
                }

                ?>
            </div>
        </div>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 table-responsive">
                <table class="table table-bordered table-hover">
                    <tr>
                        <th colspan="2">Хозяева</th>
                    </tr>
                    <tr>
                        <td>От продажи билетов получают</td>
                        <td class="text-center">
                            <?php if (TournamentType::FRIENDLY === $game->schedule->tournament_type_id) : ?>
                                50%
                            <?php elseif (TournamentType::NATIONAL === $game->schedule->tournament_type_id) : ?>
                                33%
                            <?php else : ?>
                                100%
                            <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Содержание стадиона</td>
                        <td class="text-right">
                            <?php if (TournamentType::FRIENDLY === $game->schedule->tournament_type_id) : ?>
                                <?= FormatHelper::asCurrency($game->stadium->maintenance / 2) ?>
                            <?php elseif (TournamentType::NATIONAL === $game->schedule->tournament_type_id) : ?>
                                <?= FormatHelper::asCurrency(0) ?>
                            <?php else : ?>
                                <?= FormatHelper::asCurrency($game->stadium->maintenance) ?>
                            <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Зарплата игроков за день</td>
                        <td class="text-right">
                            <?php if (TournamentType::NATIONAL === $game->schedule->tournament_type_id) : ?>
                                <?= FormatHelper::asCurrency(0) ?>
                            <?php else : ?>
                                <?= FormatHelper::asCurrency($game->homeTeam->salary) ?>
                            <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Сумма расходов хозяев</td>
                        <td class="text-right">
                            <?php if (TournamentType::FRIENDLY === $game->schedule->tournament_type_id) : ?>
                                <?= FormatHelper::asCurrency($game->stadium->maintenance / 2 + $game->homeTeam->salary) ?>
                            <?php elseif (TournamentType::NATIONAL === $game->schedule->tournament_type_id) : ?>
                                <?= FormatHelper::asCurrency(0) ?>
                            <?php else : ?>
                                <?= FormatHelper::asCurrency($game->stadium->maintenance + $game->homeTeam->salary) ?>
                            <?php endif ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <table class="table table-bordered table-hover table-responsive">
                    <tr>
                        <th colspan="2">Гости</th>
                    </tr>
                    <tr>
                        <td>От продажи билетов получают</td>
                        <td class="text-center">
                            <?php if (TournamentType::FRIENDLY === $game->schedule->tournament_type_id) : ?>
                                50%
                            <?php elseif (TournamentType::NATIONAL === $game->schedule->tournament_type_id) : ?>
                                33%
                            <?php else : ?>
                                0%
                            <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Содержание стадиона</td>
                        <td class="text-right">
                            <?php if (TournamentType::FRIENDLY === $game->schedule->tournament_type_id) : ?>
                                <?= FormatHelper::asCurrency($game->stadium->maintenance / 2) ?>
                            <?php else : ?>
                                <?= FormatHelper::asCurrency(0) ?>
                            <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Зарплата игроков за день</td>
                        <td class="text-right">
                            <?php if (TournamentType::NATIONAL === $game->schedule->tournament_type_id) : ?>
                                <?= FormatHelper::asCurrency(0) ?>
                            <?php else : ?>
                                <?= FormatHelper::asCurrency($game->guestTeam->salary) ?>
                            <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Сумма расходов гостей</td>
                        <td class="text-right">
                            <?php if (TournamentType::FRIENDLY === $game->schedule->tournament_type_id) : ?>
                                <?= FormatHelper::asCurrency($game->stadium->maintenance / 2 + $game->guestTeam->salary) ?>
                            <?php elseif (TournamentType::NATIONAL === $game->schedule->tournament_type_id) : ?>
                                <?= FormatHelper::asCurrency(0) ?>
                            <?php else : ?>
                                <?= FormatHelper::asCurrency($game->guestTeam->salary) ?>
                            <?php endif ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
