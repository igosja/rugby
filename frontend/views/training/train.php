<?php

// TODO refactor

use common\components\helpers\FormatHelper;
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
<?= Html::beginForm(['train', 'ok' => 1], 'get') ?>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= Yii::t('frontend', 'views.training.train.p') ?>:
        <ul>
            <?php foreach ($confirmData['power'] as $item) : ?>
                <li><?= $item['name'] ?> <?= Yii::t('frontend', 'views.training.train.power') ?></li>
                <?= Html::hiddenInput('power[' . $item['id'] . ']', 1) ?>
            <?php endforeach ?>
            <?php foreach ($confirmData['position'] as $item) : ?>
                <li><?= $item['name'] ?> <?= Yii::t('frontend', 'views.training.train.position') ?> <?= $item['position']['name'] ?></li>
                <?= Html::hiddenInput('position[' . $item['id'] . ']', $item['position']['id']) ?>
            <?php endforeach ?>
            <?php foreach ($confirmData['special'] as $item) : ?>
                <li><?= $item['name'] ?> <?= Yii::t('frontend', 'views.training.train.special') ?> <?= $item['special']['name'] ?></li>
                <?= Html::hiddenInput('special[' . $item['id'] . ']', $item['special']['id']) ?>
            <?php endforeach ?>
        </ul>
        <?= Yii::t('frontend', 'views.training.train.price') ?> <span
                class="strong"><?= FormatHelper::asCurrency($confirmData['price']) ?></span>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::submitButton(Yii::t('frontend', 'views.training.train.submit'), ['class' => 'btn margin']) ?>
        <?= Html::a(Yii::t('frontend', 'views.training.train.link.index'), ['index'], ['class' => 'btn margin']) ?>
    </div>
</div>
<?= Html::endForm() ?>
