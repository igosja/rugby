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
                <?= Yii::t('frontend', 'views.scout.index.scout') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= Yii::t('frontend', 'views.scout.level') ?>:
                <span class="strong"><?= $team->baseScout->level ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= Yii::t('frontend', 'views.scout.speed', [
                    'max' => $team->baseScout->scout_speed_max,
                    'min' => $team->baseScout->scout_speed_min,
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= Yii::t('frontend', 'views.scout.available', [
                    'available' => $team->availableScout(),
                    'count' => $team->baseScout->my_style_count,
                ]) ?>
            </div>
        </div>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <span class="strong"><?= Yii::t('frontend', 'views.scout.price') ?>:</span>
        <?= Yii::t('frontend', 'views.scout.price.style') ?>
        <span class="strong">
            <?= FormatHelper::asCurrency($team->baseScout->my_style_price) ?>
        </span>
    </div>
</div>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Yii::t('frontend', 'views.scout.p') ?>
    </div>
</div>
<?= Html::beginForm(['study', 'ok' => true], 'get') ?>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= Yii::t('frontend', 'views.scout.study.scout') ?>:
        <ul>
            <?php foreach ($confirmData['style'] as $item) : ?>
                <li><?= $item['name'] ?> - <?= Yii::t('frontend', 'views.scout.study.style') ?></li>
                <?= Html::hiddenInput('style[' . $item['id'] . ']', 1) ?>
            <?php endforeach ?>
        </ul>
        <?= Yii::t('frontend', 'views.scout.study.price') ?> <span
                class="strong"><?= FormatHelper::asCurrency($confirmData['price']) ?></span>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::submitButton(Yii::t('frontend', 'views.scout.study.submit'), ['class' => 'btn margin']) ?>
        <?= Html::a(Yii::t('frontend', 'views.scout.study.link.index'), ['index'], ['class' => 'btn margin']) ?>
    </div>
</div>
<?= Html::endForm() ?>
