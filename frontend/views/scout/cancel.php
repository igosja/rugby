<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use common\models\db\Scout;
use common\models\db\Team;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var int $id
 * @var Team $team
 * @var Scout $scout
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
                <?= Yii::t('frontend', 'views.scout.scout') ?>
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
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= Yii::t('frontend', 'views.scout.cancel.cancel') ?>:
        <ul>
            <li>
                <?= $scout->player->playerName() ?> - <?= Yii::t('frontend', 'views.scout.cancel.cancel.style') ?>
            </li>
        </ul>
        <?= Yii::t('frontend', 'views.scout.cancel.money') ?>
        <span class="strong"><?= FormatHelper::asCurrency($team->baseScout->my_style_price) ?></span>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <?= Html::a(Yii::t('frontend', 'views.scout.cancel.submit'), ['cancel', 'id' => $id, 'ok' => true], ['class' => 'btn margin']) ?>
        <?= Html::a(Yii::t('frontend', 'views.scout.cancel.link.index'), ['index'], ['class' => 'btn margin']) ?>
    </div>
</div>
