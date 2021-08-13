<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use common\models\db\Team;
use common\models\db\Training;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var int $id
 * @var int $price
 * @var Team $team
 * @var Training $training
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
                <?= Yii::t('frontend', 'views.training.title') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= Yii::t('frontend', 'views.training.level') ?>:
                <span class="strong"><?= $team->baseTraining->level ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= Yii::t('frontend', 'views.training.speed', [
                    'max' => $team->baseTraining->training_speed_max,
                    'min' => $team->baseTraining->training_speed_min,
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= Yii::t('frontend', 'views.training.available.power', [
                    'available' => $team->availableTrainingPower(),
                    'count' => $team->baseTraining->power_count,
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= Yii::t('frontend', 'views.training.available.special', [
                    'available' => $team->availableTrainingSpecial(),
                    'count' => $team->baseTraining->special_count,
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= Yii::t('frontend', 'views.training.available.position', [
                    'available' => $team->availableTrainingPosition(),
                    'count' => $team->baseTraining->position_count,
                ]) ?>
            </div>
        </div>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <span class="strong"><?= Yii::t('frontend', 'views.training.price') ?>:</span>
        <?= Yii::t('frontend', 'views.training.price.power') ?>
        <span class="strong">
            <?= FormatHelper::asCurrency($team->baseTraining->power_price) ?>
        </span>
        <?= Yii::t('frontend', 'views.training.price.special') ?>
        <span class="strong">
            <?= FormatHelper::asCurrency($team->baseTraining->special_price) ?>
        </span>
        <?= Yii::t('frontend', 'views.training.price.position') ?>
        <span class="strong">
            <?= FormatHelper::asCurrency($team->baseTraining->position_price) ?>
        </span>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Yii::t('frontend', 'views.training.p') ?>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= Yii::t('frontend', 'views.training.cancel.cancel') ?>:
        <ul>
            <li>
                <?= $training->player->playerName() ?>
                <?php if ($training->position_id) : ?>
                    <?= Yii::t('frontend', 'views.training.cancel.position') ?> <?= $training->position->text ?>
                <?php elseif ($training->special_id) : ?>
                    <?= Yii::t('frontend', 'views.training.cancel.special') ?> <?= $training->special->text ?>
                <?php else : ?>
                    <?= Yii::t('frontend', 'views.training.cancel.power') ?>
                <?php endif ?>
            </li>
        </ul>
        <?= Yii::t('frontend', 'views.training.cancel.money') ?> <span
                class="strong"><?= FormatHelper::asCurrency($price) ?></span>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::a(Yii::t('frontend', 'views.training.cancel.submit'), ['cancel', 'id' => $id, 'ok' => true], ['class' => 'btn margin']) ?>
        <?= Html::a(Yii::t('frontend', 'views.training.cancel.link.index'), ['index'], ['class' => 'btn margin']) ?>
    </div>
</div>
