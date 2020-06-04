<?php

use common\components\helpers\FormatHelper;
use frontend\components\AbstractController;

/**
 * @var AbstractController $controller
 */

$controller = Yii::$app->controller;
$model = $controller->myTeamOrVice;

?>
<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 text-size-2">
    <span class="italic">Показатели вашей команды:</span>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
            - Рейтинг силы команды (Vs)
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
            <?= $model->team_power_vs; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
            - Сила 15 лучших (s15)
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
            <?= $model->team_power_s_15; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
            - Сила 19 лучших (s19)
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
            <?= $model->team_power_s_19; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
            - Сила 24 лучших (s24)
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
            <?= $model->team_power_s_24; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
            - Стоимость строений
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
            <?= FormatHelper::asCurrency($model->team_price_base); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
            - Общая стоимость
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
            <?= FormatHelper::asCurrency($model->team_price_total); ?>
        </div>
    </div>
</div>
