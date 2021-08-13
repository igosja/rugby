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
            <?= Yii::t('frontend', 'views.visitor.view.h1') ?>
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
                    <?= Yii::t('frontend', 'views.visitor.view.season') ?>
                </td>
                <td class="text-right">
                    <?= $game->schedule->season_id ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?= Yii::t('frontend', 'views.visitor.view.date') ?>
                </td>
                <td class="text-right">
                    <?= FormatHelper::asDate($game->schedule->date) ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?= Yii::t('frontend', 'views.visitor.view.tournament-type') ?>
                </td>
                <td class="text-right">
                    <?= $game->schedule->tournamentType->name ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?= Yii::t('frontend', 'views.visitor.view.tournament-coefficient') ?>
                </td>
                <td class="text-right">
                    <?= $game->schedule->tournamentType->visitor / 100 ?>
                </td>
            </tr>
            <?php if (TournamentType::CHAMPIONSHIP === $game->schedule->tournament_type_id) : ?>
                <tr>
                    <td>
                        <?= Yii::t('frontend', 'views.visitor.view.division') ?>
                    </td>
                    <td class="text-right">
                        <?= $game->homeTeam->championship->division->name ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?= Yii::t('frontend', 'views.visitor.view.division-coefficient') ?>
                    </td>
                    <td class="text-right">
                        <?= Yii::$app->formatter->asDecimal((100 - ($game->homeTeam->championship->division_id - 1)) / 100) ?>
                    </td>
                </tr>
            <?php endif ?>
            <tr>
                <td>
                    <?= Yii::t('frontend', 'views.visitor.view.stage') ?>
                </td>
                <td class="text-right">
                    <?= $game->schedule->stage->name ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?= Yii::t('frontend', 'views.visitor.view.stage-coefficient') ?>
                </td>
                <td class="text-right">
                    <?= $game->schedule->stage->visitor / 100 ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?= $game->stadium->name ?> (<?= Yii::t('frontend', 'views.visitor.view.capacity') ?>)
                </td>
                <td class="text-right">
                    <?= $game->stadium->capacity ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?= Yii::t('frontend', 'views.visitor.view.visitor-home') ?>
                </td>
                <td class="text-right">
                    <?= Yii::$app->formatter->asDecimal($game->homeTeam->visitor / 100) ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?= Yii::t('frontend', 'views.visitor.view.visitor-guest') ?>
                </td>
                <td class="text-right">
                    <?= Yii::$app->formatter->asDecimal($game->guestTeam->visitor / 100) ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?= Yii::t('frontend', 'views.visitor.view.idol') ?>
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
                                    'name' => Yii::t('frontend', 'views.visitor.view.series'),
                                    'data' => $sDataVisitor,
                                ]
                            ],
                            'title' => [
                                'text' => Yii::t('frontend', 'views.visitor.view.title'),
                            ],
                            'tooltip' => [
                                'headerFormat' => Yii::t('frontend', 'views.visitor.view.header') . ' <b>{point.key}</b><br/>',
                                'pointFormat' => '{series.name} <b>{point.y}</b>',
                            ],
                            'xAxis' => [
                                'categories' => $xData,
                                'title' => [
                                    'text' => Yii::t('frontend', 'views.visitor.view.x-title'),
                                ],
                            ],
                            'yAxis' => [
                                'title' => [
                                    'text' => Yii::t('frontend', 'views.visitor.view.y-title'),
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
                                    'name' => Yii::t('frontend', 'views.visitor.view.series.2'),
                                    'data' => $sDataIncome,
                                ]
                            ],
                            'title' => [
                                'text' => Yii::t('frontend', 'views.visitor.view.title.2'),
                            ],
                            'tooltip' => [
                                'headerFormat' => Yii::t('frontend', 'views.visitor.view.header') . ' <b>{point.key}</b><br/>',
                                'pointFormat' => '{series.name} <b>{point.y}</b>',
                            ],
                            'xAxis' => [
                                'categories' => $xData,
                                'title' => [
                                    'text' => Yii::t('frontend', 'views.visitor.view.x-title'),
                                ],
                            ],
                            'yAxis' => [
                                'title' => [
                                    'text' => Yii::t('frontend', 'views.visitor.view.y-title.2'),
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
                        <th colspan="2"><?= Yii::t('frontend', 'views.visitor.view.home') ?></th>
                    </tr>
                    <tr>
                        <td><?= Yii::t('frontend', 'views.visitor.view.receive') ?></td>
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
                        <td><?= Yii::t('frontend', 'views.visitor.view.maintenance') ?></td>
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
                        <td><?= Yii::t('frontend', 'views.visitor.view.salary') ?></td>
                        <td class="text-right">
                            <?php if (TournamentType::NATIONAL === $game->schedule->tournament_type_id) : ?>
                                <?= FormatHelper::asCurrency(0) ?>
                            <?php else : ?>
                                <?= FormatHelper::asCurrency($game->homeTeam->salary) ?>
                            <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('frontend', 'views.visitor.view.outcome.home') ?></td>
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
                        <th colspan="2"><?= Yii::t('frontend', 'views.visitor.view.guest') ?></th>
                    </tr>
                    <tr>
                        <td><?= Yii::t('frontend', 'views.visitor.view.receive') ?></td>
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
                        <td><?= Yii::t('frontend', 'views.visitor.view.maintenance') ?></td>
                        <td class="text-right">
                            <?php if (TournamentType::FRIENDLY === $game->schedule->tournament_type_id) : ?>
                                <?= FormatHelper::asCurrency($game->stadium->maintenance / 2) ?>
                            <?php else : ?>
                                <?= FormatHelper::asCurrency(0) ?>
                            <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('frontend', 'views.visitor.view.salary') ?></td>
                        <td class="text-right">
                            <?php if (TournamentType::NATIONAL === $game->schedule->tournament_type_id) : ?>
                                <?= FormatHelper::asCurrency(0) ?>
                            <?php else : ?>
                                <?= FormatHelper::asCurrency($game->guestTeam->salary) ?>
                            <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('frontend', 'views.visitor.view.outcome.guest') ?></td>
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
