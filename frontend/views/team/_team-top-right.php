<?php

// TODO refactor

/**
 * @var AbstractController $controller
 * @var Team $team
 * @var View $this
 */

use common\components\helpers\FormatHelper;
use common\models\db\Team;
use frontend\components\AbstractController;
use yii\helpers\Html;
use yii\web\View;

$controller = $this->context;
$myTeamIds = [];
foreach ($controller->myTeamArray as $item) {
    $myTeamIds[] = $item->team_id;
}

?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-3 italic">
        - <?= $team->rosterPhrase() ?> -
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-top-small">
        <?php if (!$controller->user) : ?>
            <?= Html::a(
                Html::img(
                    '/img/roster/questionnaire.png',
                    [
                        'alt' => 'Зарегистрироваться',
                        'title' => 'Зарегистрироваться',
                    ]
                ),
                ['site/sign-up'],
                ['class' => 'no-underline']
            ) ?>
        <?php else: ?>
            <?php if ($team->myTeam()) : ?>
                <?= Html::a(
                    Html::img(
                        '/img/roster/friendly.png',
                        [
                            'alt' => 'Организовать товарищеский матч',
                            'title' => 'Организовать товарищеский матч',
                        ]
                    ),
                    ['friendly/index'],
                    ['class' => 'no-underline']
                ) ?>
                <?= Html::a(
                    Html::img(
                        '/img/roster/training.png',
                        [
                            'alt' => 'Тренировка игроков',
                            'title' => 'Тренировка игроков',
                        ]
                    ),
                    ['training/index'],
                    ['class' => 'no-underline']
                ) ?>
                <?= Html::a(
                    Html::img(
                        '/img/roster/scout.png',
                        [
                            'alt' => 'Изучение игроков',
                            'title' => 'Изучение игроков',
                        ]
                    ),
                    ['scout/index'],
                    ['class' => 'no-underline']
                ) ?>
                <?= Html::a(
                    Html::img(
                        '/img/roster/physical.png',
                        [
                            'alt' => 'Изменение физической формы',
                            'title' => 'Изменение физической формы',
                        ]
                    ),
                    ['physical/index'],
                    ['class' => 'no-underline']
                ) ?>
                <?= Html::a(
                    Html::img(
                        '/img/roster/school.png',
                        [
                            'alt' => 'Подготовка молодёжи',
                            'title' => 'Подготовка молодёжи',
                        ]
                    ),
                    ['school/index'],
                    ['class' => 'no-underline']
                ) ?>
                <?php if ($team->manager->isVip()): ?>
                    <?= Html::a(
                        Html::img(
                            '/img/roster/planning.png',
                            [
                                'alt' => 'Планирование усталости',
                                'title' => 'Планирование усталости',
                            ]
                        ),
                        ['planning/index'],
                        ['class' => 'no-underline']
                    ) ?>
                <?php endif ?>
            <?php elseif (!$team->team_user_id && !in_array($team->team_id, $myTeamIds, true)): ?>
                <?= Html::a(
                    Html::img(
                        '/img/roster/free-team.png',
                        [
                            'alt' => 'Подать заявку на получение команды',
                            'title' => 'Подать заявку на получение команды',
                        ]
                    ),
                    [($controller->myTeam ? 'team/change' : 'team-request/index'), 'id' => $team->team_id],
                    ['class' => 'no-underline']
                ) ?>
            <?php endif ?>
            <?= Html::a(
                Html::img(
                    '/img/roster/questionnaire.png',
                    [
                        'alt' => 'Личные данные',
                        'title' => 'Личные данные',
                    ]
                ),
                ['user/questionnaire'],
                ['class' => 'no-underline']
            ) ?>
        <?php endif ?>
    </div>
    <?php foreach ($team->latestGame() as $item) : ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-3 italic">
            <?= FormatHelper::asDatetime($item->schedule->schedule_date) ?>
            -
            <?= $item->schedule->tournamentType->tournament_type_name ?>
            -
            <?= $item->game_home_team_id === $team->team_id ? 'Д' : 'Г' ?>
            -
            <?= Html::a(
                $item->game_home_team_id === $team->team_id ? $item->teamGuest->team_name : $item->teamHome->team_name,
                [
                    'team/view',
                    'id' =>
                        $item->game_home_team_id === $team->team_id
                            ? $item->game_guest_team_id
                            : $item->game_home_team_id
                ]
            ) ?>
            -
            <?= Html::a(
                $item->game_home_team_id === $team->team_id
                    ? $item->game_home_points . ':' . $item->game_guest_points
                    : $item->game_guest_points . ':' . $item->game_home_points,
                ['game/view', 'id' => $item->game_id]
            ) ?>
        </div>
    <?php endforeach ?>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row text-size-4">&nbsp;</div>
    </div>
    <?php foreach ($team->nearestGame() as $item) : ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-3 italic">
            <?= FormatHelper::asDatetime($item->schedule->schedule_date) ?>
            -
            <?= $item->schedule->tournamentType->tournament_type_name ?>
            -
            <?= $item->game_home_team_id === $team->team_id ? 'Д' : 'Г' ?>
            -
            <?= Html::a(
                $item->game_home_team_id === $team->team_id ? $item->teamGuest->team_name : $item->teamHome->team_name,
                [
                    'team/view',
                    'id' =>
                        $item->game_home_team_id === $team->team_id
                            ? $item->game_guest_team_id
                            : $item->game_home_team_id
                ]
            ) ?>
            -
            <?php if ($team->myTeamOrVice()) : ?>
                <?= Html::a(
                    (($item->game_home_team_id === $team->team_id && $item->game_home_tactic_id)
                        || ($item->game_guest_team_id === $team->team_id && $item->game_guest_tactic_id))
                        ? 'Отпр.'
                        : 'Ред.',
                    ['lineup/view', 'id' => $item->game_id]
                ) ?>
            <?php else: ?>
                <?= Html::a(
                    '?:?',
                    ['game/preview', 'id' => $item->game_id]
                ) ?>
            <?php endif ?>
        </div>
    <?php endforeach ?>
</div>
