<?php

use common\components\helpers\FormatHelper;
use common\models\db\Player;
use common\models\db\Squad;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/**
 * @var Player $player
 * @var View $this
 */

if ($player->myPlayer() || $player->myNationalPlayer()) {
    if ('view' === Yii::$app->controller->action->id) {
        $squadArray = Squad::find()->all();
        $squadStyle = [];
        foreach ($squadArray as $item) {
            $squadStyle[$item->squad_id] = ['style' => ['background-color' => '#' . $item->squad_color]];
        }
    }
}

?>
<div class="row margin-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-size-1 strong">
                <?= $player->playerName() ?>
            </div>
            <?php
            if (isset($squadArray)): ?>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <?php
                    if ($player->myPlayer()): ?>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
                                <label for="select-line">Состав:</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <?= Html::dropDownList(
                                    'squad_id',
                                    $player->player_squad_id,
                                    ArrayHelper::map($squadArray, 'squad_id', 'squad_name'),
                                    [
                                        'class' => 'form-control',
                                        'data' => ['url' => Url::to(['squad', 'id' => $player->player_id])],
                                        'id' => 'select-squad',
                                        'options' => $squadStyle,
                                    ]
                                ) ?>
                            </div>
                        </div>
                    <?php
                    endif ?>
                    <?php
                    if ($player->myNationalPlayer()): ?>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
                                <label for="select-line">Состав в сборной:</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <?= Html::dropDownList(
                                    'squad_id',
                                    $player->player_national_squad_id,
                                    ArrayHelper::map($squadArray, 'squad_id', 'squad_name'),
                                    [
                                        'class' => 'form-control',
                                        'data' => ['url' => Url::to(['national-squad', 'id' => $player->player_id])],
                                        'id' => 'select-national-squad',
                                        'options' => $squadStyle,
                                    ]
                                ) ?>
                            </div>
                        </div>
                    <?php
                    endif ?>
                </div>
            <?php
            endif ?>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-top">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        Национальность:
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= $player->country->countryLink() ?>
                        <?= $player->iconNational() ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        Возраст:
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= $player->player_age ?>
                        <?= $player->iconPension() ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        Сила:
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= $player->player_power_nominal ?>
                        <?= $player->iconDeal() ?>
                        <?= $player->iconTraining() ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        Усталость:
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= $player->playerTire() ?>
                        <?= $player->iconInjury() ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        Форма:
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= $player->playerPhysical() ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        Реальная сила:
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?php
                        if ($player->myPlayer()) : ?>
                            <?= $player->player_power_real ?>
                        <?php
                        else: ?>
                            ~<?= $player->player_power_nominal ?>
                        <?php
                        endif ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        Стиль:
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= $player->iconStyle() ?>
                        <?= $player->iconScout() ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        Команда:
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= $player->team->teamLink('img') ?>
                    </div>
                </div>
                <?php
                if ($player->loanTeam->team_id) : ?>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            В аренде:
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <?= $player->loanTeam->teamLink('img') ?>
                            <?= $player->iconLoan() ?>
                        </div>
                    </div>
                <?php
                endif ?>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        Позиция:
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= $player->position() ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        Спецвозможности:
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= $player->special() ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        Зарплата в день:
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= FormatHelper::asCurrency($player->player_salary) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        Стоимость:
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= FormatHelper::asCurrency($player->player_price) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>