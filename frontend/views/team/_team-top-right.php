<?php

// TODO refactor

/**
 * @var AbstractController $controller
 * @var Team $team
 * @var View $this
 */

use common\components\helpers\FormatHelper;
use common\models\db\Team;
use frontend\controllers\AbstractController;
use yii\helpers\Html;
use yii\web\View;

$controller = $this->context;
$myTeamIds = [];
foreach ($controller->myTeamArray as $item) {
    $myTeamIds[] = $item->id;
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
                        'alt' => Yii::t('frontend', 'views.team.team-top-right.sign-up'),
                        'title' => Yii::t('frontend', 'views.team.team-top-right.sign-up'),
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
                            'alt' => Yii::t('frontend', 'views.team.team-top-right.friendly'),
                            'title' => Yii::t('frontend', 'views.team.team-top-right.friendly'),
                        ]
                    ),
                    ['friendly/index'],
                    ['class' => 'no-underline']
                ) ?>
                <?= Html::a(
                    Html::img(
                        '/img/roster/training.png',
                        [
                            'alt' => Yii::t('frontend', 'views.team.team-top-right.training'),
                            'title' => Yii::t('frontend', 'views.team.team-top-right.training'),
                        ]
                    ),
                    ['training/index'],
                    ['class' => 'no-underline']
                ) ?>
                <?= Html::a(
                    Html::img(
                        '/img/roster/scout.png',
                        [
                            'alt' => Yii::t('frontend', 'views.team.team-top-right.scout'),
                            'title' => Yii::t('frontend', 'views.team.team-top-right.scout'),
                        ]
                    ),
                    ['scout/index'],
                    ['class' => 'no-underline']
                ) ?>
                <?= Html::a(
                    Html::img(
                        '/img/roster/physical.png',
                        [
                            'alt' => Yii::t('frontend', 'views.team.team-top-right.physical'),
                            'title' => Yii::t('frontend', 'views.team.team-top-right.physical'),
                        ]
                    ),
                    ['physical/index'],
                    ['class' => 'no-underline']
                ) ?>
                <?= Html::a(
                    Html::img(
                        '/img/roster/school.png',
                        [
                            'alt' => Yii::t('frontend', 'views.team.team-top-right.school'),
                            'title' => Yii::t('frontend', 'views.team.team-top-right.school'),
                        ]
                    ),
                    ['school/index'],
                    ['class' => 'no-underline']
                ) ?>
                <?php if ($team->user->isVip()): ?>
                    <?= Html::a(
                        Html::img(
                            '/img/roster/planning.png',
                            [
                                'alt' => Yii::t('frontend', 'views.team.team-top-right.planning'),
                                'title' => Yii::t('frontend', 'views.team.team-top-right.planning'),
                            ]
                        ),
                        ['planning/index'],
                        ['class' => 'no-underline']
                    ) ?>
                <?php endif ?>
            <?php elseif (!$team->user_id && !in_array($team->id, $myTeamIds, true)): ?>
                <?= Html::a(
                    Html::img(
                        '/img/roster/free-team.png',
                        [
                            'alt' => Yii::t('frontend', 'views.team.team-top-right.change'),
                            'title' => Yii::t('frontend', 'views.team.team-top-right.change'),
                        ]
                    ),
                    [($controller->myTeam ? 'team-change/change' : 'team-request/request'), 'id' => $team->id],
                    ['class' => 'no-underline']
                ) ?>
            <?php endif ?>
            <?= Html::a(
                Html::img(
                    '/img/roster/questionnaire.png',
                    [
                        'alt' => Yii::t('frontend', 'views.team.team-top-right.questionnaire'),
                        'title' => Yii::t('frontend', 'views.team.team-top-right.questionnaire'),
                    ]
                ),
                ['user/questionnaire'],
                ['class' => 'no-underline']
            ) ?>
        <?php endif ?>
    </div>
    <?php foreach ($team->latestGame() as $item) : ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-3 italic">
            <?= FormatHelper::asDatetime($item->schedule->date) ?>
            -
            <?= $item->schedule->tournamentType->name ?>
            -
            <?= $item->home_team_id === $team->id ? Yii::t('frontend', 'views.home') : Yii::t('frontend', 'views.guest') ?>
            -
            <?= Html::a(
                $item->home_team_id === $team->id ? $item->guestTeam->name : $item->homeTeam->name,
                [
                    'team/view',
                    'id' => $item->home_team_id === $team->id ? $item->guest_team_id : $item->home_team_id
                ]
            ) ?>
            -
            <?= Html::a(
                $item->home_team_id === $team->id
                    ? $item->home_point . ':' . $item->guest_point
                    : $item->guest_point . ':' . $item->home_point,
                ['game/view', 'id' => $item->id]
            ) ?>
        </div>
    <?php endforeach ?>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row text-size-4">&nbsp;</div>
    </div>
    <?php foreach ($team->nearestGame() as $item) : ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-3 italic">
            <?= FormatHelper::asDatetime($item->schedule->date) ?>
            -
            <?= $item->schedule->tournamentType->name ?>
            -
            <?= $item->home_team_id === $team->id ? Yii::t('frontend', 'views.home') : Yii::t('frontend', 'views.guest') ?>
            -
            <?= Html::a(
                $item->home_team_id === $team->id ? $item->guestTeam->name : $item->homeTeam->name,
                [
                    'team/view',
                    'id' =>
                        $item->home_team_id === $team->id
                            ? $item->guest_team_id
                            : $item->home_team_id
                ]
            ) ?>
            -
            <?php if ($team->myTeamOrVice()) : ?>
                <?= Html::a(
                    (($item->home_team_id === $team->id && $item->home_tactic_id)
                        || ($item->guest_team_id === $team->id && $item->guest_tactic_id))
                        ? Yii::t('frontend', 'views.team.team-top-right.update')
                        : Yii::t('frontend', 'views.team.team-top-right.create'),
                    ['lineup/view', 'id' => $item->id]
                ) ?>
            <?php else: ?>
                <?= Html::a(
                    '?:?',
                    ['game/preview', 'id' => $item->id]
                ) ?>
            <?php endif ?>
        </div>
    <?php endforeach ?>
</div>
