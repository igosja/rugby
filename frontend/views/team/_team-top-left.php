<?php

// TODO refactor

/**
 * @var Team $team
 */

use common\components\helpers\FormatHelper;
use common\models\db\Team;
use rmrevin\yii\fontawesome\FAR;
use rmrevin\yii\fontawesome\FAS;
use rmrevin\yii\fontawesome\FontAwesome;
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
                <?= Yii::t('frontend', 'views.team.team-top-left.off-season') ?> <?= $team->offSeason() ?>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-3">
                <?= Yii::t('frontend', 'views.team.team-top-left.division') ?> <?= $team->division() ?>
            </div>
        </div>
        <div class="row margin-top-small">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= Yii::t('frontend', 'views.team.team-top-left.manager') ?>
                <?php if ($team->user->canDialog()) : ?>
                    <?= Html::a(
                        FAR::icon(FontAwesome::_ENVELOPE),
                        ['messenger/view', 'id' => $team->user->id],
                        ['title' => Yii::t('frontend', 'views.team.team-top-left.title.messenger')]
                    ) ?>
                <?php endif ?>
                <?= Html::a(
                    $team->user->fullName(),
                    ['user/view', 'id' => $team->user->id],
                    ['class' => 'strong']
                ) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= Yii::t('frontend', 'views.team.team-top-left.nick') ?>
                <?= $team->user->iconVip() ?>
                <?= $team->user->getUserLink(['class' => 'strong']) ?>
            </div>
        </div>
        <?php if ($team->vice_user_id) : ?>
            <div class="row margin-top-small">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?= Yii::t('frontend', 'views.team.team-top-left.vice') ?>
                    <?php if ($team->viceUser->canDialog()) : ?>
                        <?= Html::a(
                            FAR::icon(FontAwesome::_ENVELOPE),
                            ['messenger/view', 'id' => $team->viceUser->id],
                            ['title' => Yii::t('frontend', 'views.team.team-top-left.title.messenger')]
                        ) ?>
                    <?php endif ?>
                    <?= Html::a(
                        $team->viceUser->fullName(),
                        ['user/view', 'id' => $team->viceUser->id],
                        ['class' => 'strong']
                    ) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?= Yii::t('frontend', 'views.team.team-top-left.nick') ?>
                    <?= $team->viceUser->iconVip() ?>
                    <?= $team->viceUser->getUserLink(['class' => 'strong']) ?>
                    <?php if ($team->canViceLeave()) : ?>
                        <?= Html::a(
                            FAS::icon(FontAwesome::_SIGN_OUT_ALT),
                            ['team/vice-leave', 'id' => $team->team_id],
                            ['title' => Yii::t('frontend', 'views.team.team-top-left.title.vice-leave')]
                        ) ?>
                    <?php endif ?>
                </div>
            </div>
        <?php endif ?>
        <div class="row margin-top-small">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= Yii::t('frontend', 'views.team.team-top-left.stadium') ?>
                <?= $team->stadium->name ?>,
                <strong><?= Yii::$app->formatter->asInteger($team->stadium->capacity) ?></strong>
                <?php if ($team->myTeam()) : ?>
                    <?= Html::a(
                        FAS::icon(FontAwesome::_SEARCH),
                        ['stadium/increase']
                    ) ?>
                <?php endif ?>
                <?php if ($team->buildingStadium) : ?>
                    <?= FAS::icon(FontAwesome::_COG, ['title' => Yii::t('frontend', 'views.team.team-top-left.title.building-stadium')]) ?>
                <?php endif ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= Yii::t('frontend', 'views.team.team-top-left.base') ?>
                <?= Yii::t('frontend', 'views.team.team-top-left.level', [
                    'level' => $team->base->level,
                    'max' => $team->base->slot_max,
                    'used' => $team->baseUsed(),
                ]) ?>
                <?= Html::a(
                    FAS::icon(FontAwesome::_SEARCH),
                    ['base/view', 'id' => $team->id]
                ) ?>
                <?php if ($team->buildingBase) : ?>
                    <?= FAS::icon(FontAwesome::_COG, ['title' => Yii::t('frontend', 'views.team.team-top-left.title.building.base')]) ?>
                <?php endif ?>
            </div>
        </div>
        <div class="row margin-top-small">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= Yii::t('frontend', 'views.team.team-top-left.finance') ?>
                <span class="strong">
                    <?= FormatHelper::asCurrency($team->finance) ?>
                </span>
            </div>
        </div>
    </div>
</div>
