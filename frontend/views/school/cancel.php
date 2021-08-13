<?php

// TODO refactor

use common\models\db\School;
use common\models\db\Team;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var int $id
 * @var Team $team
 * @var School $school
 * @var View $this
 */

?>
<div class="row margin-top">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?= $this->render('//team/_team-top-left', ['team' => $team]) ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong text-size-1">
                <?= Yii::t('frontend', 'views.school.title') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= Yii::t('frontend', 'views.school.level') ?>:
                <span class="strong"><?= $team->baseSchool->level ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= Yii::t('frontend', 'views.school.speed', ['speed' => $team->baseSchool->school_speed]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= Yii::t('frontend', 'views.school.available', [
                    'available' => $team->availableSchool(),
                    'count' => $team->baseSchool->player_count,
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= Yii::t('frontend', 'views.school.with-special', [
                    'available' => $team->availableSchoolWithSpecial(),
                    'count' => $team->baseSchool->with_special,
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= Yii::t('frontend', 'views.school.with-style', [
                    'available' => $team->availableSchoolWithStyle(),
                    'count' => $team->baseSchool->with_style,
                ]) ?>
            </div>
        </div>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Yii::t('frontend', 'views.school.p') ?>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= Yii::t('frontend', 'views.school.cancel.text') ?>:
        <ul>
            <li><?= Yii::t('frontend', 'views.school.position') ?> - <?= $school->position->name ?></li>
            <li>
                <?= Yii::t('frontend', 'views.school.special') ?> -
                <?= $school->is_with_special ? $school->special->name : Yii::t('frontend', 'views.school.unknown') ?>
            </li>
            <li>
                <?= Yii::t('frontend', 'views.school.style') ?> -
                <?= $school->is_with_style ? $school->style->name : Yii::t('frontend', 'views.school.unknown') ?>
            </li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::a(Yii::t('frontend', 'views.school.cancel.link.ok'), ['cancel', 'id' => $id, 'ok' => true], ['class' => 'btn margin']) ?>
        <?= Html::a(Yii::t('frontend', 'views.school.cancel.link.index'), ['index'], ['class' => 'btn margin']) ?>
    </div>
</div>
