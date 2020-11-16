<?php

// TODO refactor

/**
 * @var Team $team
 */

use common\components\helpers\FormatHelper;
use common\models\db\Team;
use rmrevin\yii\fontawesome\FAS;
use yii\helpers\Html;

?>
<div class="row">
    <div class="col-lg-3 col-md-4 col-sm-3 col-xs-4 text-center team-logo-div">
        <?= Html::a(
            $team->getLogo(),
            ['team/logo', 'id' => $team->id],
            ['class' => 'team-logo-link']
        ) ?>
    </div>
    <div class="col-lg-9 col-md-8 col-sm-9 col-xs-8">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-1 strong">
                <?= $team->iconFreeTeam() ?>
                <?= $team->fullName() ?>
            </div>
        </div>
        <div class="row margin-top-small">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-3">
                Кубок межсезонья: <?= $team->offSeason() ?>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-3">
                Дивизион: <?= $team->division() ?>
            </div>
        </div>
        <div class="row margin-top-small">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                Менеджер:
                <?php if ($team->user->canDialog()) : ?>
                    <?= Html::a(
                        FAS::icon(FAS::_ENVELOPE),
                        ['messenger/view', 'id' => $team->user->id],
                        ['title' => 'Написать']
                    ) ?>
                <?php endif ?>
                <?= Html::a(
                    $team->user->getFullName(),
                    ['user/view', 'id' => $team->user->id],
                    ['class' => 'strong']
                ) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                Ник:
                <?= $team->user->iconVip() ?>
                <?= $team->user->getUserLink(['class' => 'strong']) ?>
            </div>
        </div>
        <?php if ($team->vice_user_id) : ?>
            <div class="row margin-top-small">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    Заместитель:
                    <?php if ($team->viceUser->canDialog()) : ?>
                        <?= Html::a(
                            FAS::icon(FAS::_ENVELOPE),
                            ['messenger/view', 'id' => $team->viceUser->id],
                            ['title' => 'Написать']
                        ) ?>
                    <?php endif ?>
                    <?= Html::a(
                        $team->viceUser->getFullName(),
                        ['user/view', 'id' => $team->viceUser->id],
                        ['class' => 'strong']
                    ) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    Ник:
                    <?= $team->viceUser->iconVip() ?>
                    <?= $team->viceUser->getUserLink(['class' => 'strong']) ?>
                    <?php if ($team->canViceLeave()) : ?>
                        <?= Html::a(
                            FAS::icon(FAS::_SIGN_OUT_ALT),
                            ['team/vice-leave', 'id' => $team->team_id],
                            ['title' => 'Отказаться от заместительства']
                        ) ?>
                    <?php endif ?>
                </div>
            </div>
        <?php endif ?>
        <div class="row margin-top-small">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                Стадион:
                <?= $team->stadium->name ?>,
                <strong><?= Yii::$app->formatter->asInteger($team->stadium->capacity) ?></strong>
                <?php if ($team->myTeam()) : ?>
                    <?= Html::a(
                        FAS::icon(FAS::_SEARCH),
                        ['stadium/increase']
                    ) ?>
                <?php endif ?>
                <?php if ($team->buildingStadium) : ?>
                    <?= FAS::icon(FAS::_COG, ['title' => 'На стадионе идет строительство']) ?>
                <?php endif ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                База:
                <span class="strong"><?= $team->base->level ?></span> уровень
                (<span class="strong"><?= $team->baseUsed() ?></span> из
                <span class="strong"><?= $team->base->slot_max ?></span>)
                <?= Html::a(
                    FAS::icon(FAS::_SEARCH),
                    ['base/view', 'id' => $team->id]
                ) ?>
                <?php if ($team->buildingBase) : ?>
                    <?= FAS::icon(FAS::_COG, ['title' => 'На базе идет строительство']) ?>
                <?php endif ?>
            </div>
        </div>
        <div class="row margin-top-small">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                Финансы:
                <span class="strong">
                    <?= FormatHelper::asCurrency($team->finance) ?>
                </span>
            </div>
        </div>
    </div>
</div>
