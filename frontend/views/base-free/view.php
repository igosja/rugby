<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use common\models\db\Team;
use yii\helpers\Html;

/**
 * @var bool $delBase
 * @var bool $delMedical
 * @var bool $delPhysical
 * @var bool $delSchool
 * @var bool $delScout
 * @var bool $delTraining
 * @var array $linkBaseArray
 * @var array $linkMedicalArray
 * @var array $linkPhysicalArray
 * @var array $linkSchoolArray
 * @var array $linkScoutArray
 * @var array $linkTrainingArray
 * @var Team $myTeam
 * @var Team $team
 */

?>
<div class="row margin-top">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?= $this->render('//team/_team-top-left', ['team' => $team, 'teamId' => $team->id]) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"></div>
</div>
<?php if ($team->buildingBase) : ?>
    <div class="row margin-top">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert info">
            На базе сейчас идет строительство.
            Дата окончания строительства - <?= $team->buildingBase->endDate() ?>
        </div>
    </div>
<?php endif ?>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        Здесь вы можете использовать бесплатные улучшения базы.
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        Осталось бесплатных улучшений:
        <span class="strong"><?= $team->free_base_number ?></span>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <fieldset>
            <legend class="strong text-center">База команды</legend>
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6 text-center">
                    <?= Html::img(
                        '/img/base/base.png',
                        [
                            'alt' => 'База команды',
                            'class' => 'img-border img-base',
                        ]
                    ) ?>
                </div>
                <div class="col-lg-9 col-md-8 col-sm-7 col-xs-6">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12<?php if ($delBase) : ?> del<?php endif ?>">
                            Уровень:
                            <span class="strong"><?= $team->base->level ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12<?php if ($delBase) : ?> del<?php endif ?>">
                            Стоимость:
                            <span class="strong">
                                <?= FormatHelper::asCurrency($team->base->price_buy) ?>
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12<?php if ($delBase) : ?> del<?php endif ?>">
                            Слотов:
                            <span class="strong">
                                <?= $team->base->slot_min ?>-<?= $team->base->slot_max ?>
                            </span>
                        </div>
                    </div>
                    <div class="row margin-top">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12<?php if ($delBase) : ?> del<?php endif ?>">
                            Занято слотов:
                            <span class="strong"><?= $team->baseUsed() ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12<?php if ($delBase) : ?> del<?php endif ?>">
                            Содержание:
                            <span class="strong">
                                <?= FormatHelper::asCurrency($team->baseMaintenance()) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($linkBaseArray) : ?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        <?= implode(' ', $linkBaseArray) ?>
                    </div>
                </div>
            <?php endif ?>
        </fieldset>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <fieldset>
            <legend class="strong text-center">Тренировочный центр</legend>
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6 text-center">
                    <?= Html::img(
                        '/img/base/training.png',
                        [
                            'alt' => 'Тренировочный центр',
                            'class' => 'img-border img-base',
                        ]
                    ) ?>
                </div>
                <div class="col-lg-9 col-md-8 col-sm-7 col-xs-6">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12<?php if ($delTraining) : ?> del<?php endif ?>">
                            Уровень:
                            <span class="strong"><?= $team->baseTraining->level ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12<?php if ($delTraining) : ?> del<?php endif ?>">
                            Доступно:
                            <span class="strong"><?= $team->baseTraining->power_count ?></span> бал.
                            |
                            <span class="strong"><?= $team->baseTraining->special_count ?></span> спец.
                            |
                            <span class="strong"><?= $team->baseTraining->position_count ?></span> поз.
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12<?php if ($delTraining) : ?> del<?php endif ?>">
                            Скорость:
                            <span class="strong">
                                <?=
                                $team->baseTraining->training_speed_min
                                ?>-<?=
                                $team->baseTraining->training_speed_max
                                ?>%
                            </span>
                        </div>
                    </div>
                    <div class="row margin-top">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            Осталось:
                            <span class="strong"><?= $team->availableTrainingPower() ?></span>
                            бал.
                            |
                            <span class="strong"><?= $team->availableTrainingSpecial() ?></span>
                            спец.
                            |
                            <span class="strong"><?= $team->availableTrainingPosition() ?></span>
                            поз.
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($linkTrainingArray) : ?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        <?= implode(' ', $linkTrainingArray) ?>
                    </div>
                </div>
            <?php endif ?>
        </fieldset>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <fieldset>
            <legend class="strong text-center">Медцентр</legend>
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6 text-center">
                    <?= Html::img(
                        '/img/base/medical.png',
                        [
                            'alt' => 'Медицинский центр',
                            'class' => 'img-border img-base',
                        ]
                    ) ?>
                </div>
                <div class="col-lg-9 col-md-8 col-sm-7 col-xs-6">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12<?php if ($delMedical) : ?> del<?php endif ?>">
                            Уровень:
                            <span class="strong"><?= $team->baseMedical->level ?></span>
                        </div>
                    </div>
                    <div class="row margin-top">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12<?php if ($delMedical) : ?> del<?php endif ?>">
                            Базовая усталость:
                            <span class="strong"><?= $team->baseMedical->tire ?>%</span>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($linkMedicalArray) : ?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        <?= implode(' ', $linkMedicalArray) ?>
                    </div>
                </div>
            <?php endif ?>
        </fieldset>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <fieldset>
            <legend class="strong text-center">Центр физподготовки</legend>
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6 text-center">
                    <?= Html::img(
                        '/img/base/physical.png',
                        [
                            'alt' => 'Центр физподготовки',
                            'class' => 'img-border img-base',
                        ]
                    ) ?>
                </div>
                <div class="col-lg-9 col-md-8 col-sm-7 col-xs-6">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12<?php if ($delPhysical) : ?> del<?php endif ?>">
                            Уровень:
                            <span class="strong"><?= $team->basePhysical->level ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12<?php if ($delPhysical) : ?> del<?php endif ?>">
                            Изменений формы:
                            <span class="strong"><?= $team->basePhysical->change_count ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12<?php if ($delPhysical) : ?> del<?php endif ?>">
                            Увеличение усталости:
                            <span class="strong"><?= $team->basePhysical->tire_bonus ?>%</span>
                        </div>
                    </div>
                    <?php if ($myTeam && $myTeam->id === $team->id) : ?>
                        <div class="row margin-top">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12<?php if ($delPhysical) : ?> del<?php endif ?>">
                                Осталось изменений:
                                <span class="strong"><?= $team->availablePhysical() ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12<?php if ($delPhysical) : ?> del<?php endif ?>">
                                Запланировано: <span class="strong"><?= $team->planPhysical() ?></span>
                            </div>
                        </div>
                    <?php endif ?>
                </div>
            </div>
            <?php if ($linkPhysicalArray) : ?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        <?= implode(' ', $linkPhysicalArray) ?>
                    </div>
                </div>
            <?php endif ?>
        </fieldset>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <fieldset>
            <legend class="strong text-center">Спортшкола</legend>
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6 text-center">
                    <?= Html::img(
                        '/img/base/school.png',
                        [
                            'alt' => 'Спортивная школа',
                            'class' => 'img-border img-base',
                        ]
                    ) ?>
                </div>
                <div class="col-lg-9 col-md-8 col-sm-7 col-xs-6">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12<?php if ($delSchool) : ?> del<?php endif ?>">
                            Уровень:
                            <span class="strong"><?= $team->baseSchool->level ?></span>
                        </div>
                    </div>
                    <div class="row margin-top">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12<?php if ($delSchool) : ?> del<?php endif ?>">
                            Молодежь: <span class="strong"><?= $team->baseSchool->player_count ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12<?php if ($delSchool) : ?> del<?php endif ?>">
                            Осталось игроков: <span class="strong"><?= $team->availableSchool() ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($linkSchoolArray) : ?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        <?= implode(' ', $linkSchoolArray) ?>
                    </div>
                </div>
            <?php endif ?>
        </fieldset>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <fieldset>
            <legend class="strong text-center">Скаут-центр</legend>
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6 text-center">
                    <?= Html::img(
                        '/img/base/scout.png',
                        [
                            'alt' => 'Скаут-центр',
                            'class' => 'img-border img-base',
                        ]
                    ) ?>
                </div>
                <div class="col-lg-9 col-md-8 col-sm-7 col-xs-6">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12<?php if ($delScout) : ?> del<?php endif ?>">
                            Уровень: <span class="strong"><?= $team->baseScout->level ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12<?php if ($delScout) : ?> del<?php endif ?>">
                            Изучений стилей:
                            <span class="strong"><?= $team->baseScout->my_style_count ?></span>
                        </div>
                    </div>
                    <div class="row margin-top">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12<?php if ($delScout) : ?> del<?php endif ?>">
                            Осталось изучений стилей:
                            <span class="strong"><?= $team->availableScout() ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($linkScoutArray) : ?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        <?= implode(' ', $linkScoutArray) ?>
                    </div>
                </div>
            <?php endif ?>
        </fieldset>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::a('В ростер', ['team/view', 'id' => $team->id], ['class' => 'btn margin']) ?>
    </div>
</div>