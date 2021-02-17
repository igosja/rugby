<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use frontend\controllers\AbstractController;

/**
 * @var AbstractController $controller
 */

$controller = Yii::$app->controller;
$model = $controller->myTeamOrVice;

?>
<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 text-size-2">
    <span class="italic"><?= Yii::t('frontend', 'views.team.team-bottom-my-title.title') ?>:</span>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
            - <?= Yii::t('frontend', 'views.team.team-bottom-my-title.vs') ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
            <?= $model->power_vs ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
            - <?= Yii::t('frontend', 'views.team.team-bottom-my-title.s15') ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
            <?= $model->power_s_15 ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
            - <?= Yii::t('frontend', 'views.team.team-bottom-my-title.s19') ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
            <?= $model->power_s_19 ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
            - <?= Yii::t('frontend', 'views.team.team-bottom-my-title.s24') ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
            <?= $model->power_s_24 ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
            - <?= Yii::t('frontend', 'views.team.team-bottom-my-title.price.base') ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
            <?= FormatHelper::asCurrency($model->price_base) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
            - <?= Yii::t('frontend', 'views.team.team-bottom-my-title.price') ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
            <?= FormatHelper::asCurrency($model->price_total) ?>
        </div>
    </div>
</div>
