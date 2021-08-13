<?php

// TODO refactor

use common\models\db\Team;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var array $confirmData
 * @var Team $team
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
<?= Html::beginForm(['school/start', 'ok' => 1], 'get') ?>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= Yii::t('frontend', 'views.school.start.text') ?>:
        <ul>
            <li><?= Yii::t('frontend', 'views.school.position') ?> - <?= $confirmData['position']['name'] ?></li>
            <li>
                <?= Yii::t('frontend', 'views.school.special') ?> -
                <?= $confirmData['special']['with'] ? $confirmData['special']['name'] : Yii::t('frontend', 'views.school.no') ?>
            </li>
            <li><?= Yii::t('frontend', 'views.school.style') ?>
                - <?= $confirmData['style']['with'] ? $confirmData['style']['name'] : Yii::t('frontend', 'views.school.unknown') ?></li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::hiddenInput('position_id', $confirmData['position']['id']) ?>
        <?= Html::hiddenInput('special_id', $confirmData['special']['with']) ?>
        <?= Html::hiddenInput('style_id', $confirmData['style']['with']) ?>
        <?= Html::submitButton(Yii::t('frontend', 'views.school.start.submit'), ['class' => 'btn margin']) ?>
        <?= Html::a(Yii::t('frontend', 'views.school.start.link.index'), ['school/index'], ['class' => 'btn margin']) ?>
    </div>
</div>
<?= Html::endForm() ?>
