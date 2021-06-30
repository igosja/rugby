<?php

// TODO refactor

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
            $squadStyle[$item->id] = ['style' => ['background-color' => '#' . $item->color]];
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
            <?php if (isset($squadArray)): ?>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <?php if ($player->myPlayer()): ?>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
                                <label for="select-line"><?= Yii::t('frontend', 'views.player.player.squad') ?>:</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <?= Html::dropDownList(
                                    'squad_id',
                                    $player->squad_id,
                                    ArrayHelper::map($squadArray, 'id', 'name'),
                                    [
                                        'class' => 'form-control',
                                        'data' => ['url' => Url::to(['squad', 'id' => $player->id])],
                                        'id' => 'select-squad',
                                        'options' => $squadStyle,
                                    ]
                                ) ?>
                            </div>
                        </div>
                    <?php endif ?>
                    <?php if ($player->myNationalPlayer()): ?>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
                                <label for="select-line"><?= Yii::t('frontend', 'views.player.player.squad.national') ?>
                                    :</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <?= Html::dropDownList(
                                    'squad_id',
                                    $player->national_squad_id,
                                    ArrayHelper::map($squadArray, 'id', 'name'),
                                    [
                                        'class' => 'form-control',
                                        'data' => ['url' => Url::to(['national-squad', 'id' => $player->id])],
                                        'id' => 'select-national-squad',
                                        'options' => $squadStyle,
                                    ]
                                ) ?>
                            </div>
                        </div>
                    <?php endif ?>
                </div>
            <?php endif ?>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-top">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= Yii::t('frontend', 'views.player.player.national') ?>:
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= $player->country->getTextLink() ?>
                        <?= $player->iconNational() ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= Yii::t('frontend', 'views.player.player.age') ?>:
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= $player->age ?>
                        <?= $player->iconPension() ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= Yii::t('frontend', 'views.player.player.power') ?>:
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= $player->power_nominal ?>
                        <?= $player->iconDeal() ?>
                        <?= $player->iconTraining() ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= Yii::t('frontend', 'views.player.player.tire') ?>:
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= $player->playerTire() ?>
                        <?= $player->iconInjury() ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= Yii::t('frontend', 'views.player.player.physical') ?>:
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= $player->playerPhysical() ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= Yii::t('frontend', 'views.player.player.real-power') ?>:
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?php if ($player->myPlayer()) : ?>
                            <?= $player->power_real ?>
                        <?php else: ?>
                            ~<?= $player->power_nominal ?>
                        <?php endif ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= Yii::t('frontend', 'views.player.player.style') ?>:
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
                        <?= Yii::t('frontend', 'views.player.player.team') ?>:
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= $player->team->getTeamLink() ?>
                    </div>
                </div>
                <?php if ($player->loan_team_id) : ?>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <?= Yii::t('frontend', 'views.player.player.loan') ?>:
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <?= $player->loanTeam->getTeamLink() ?>
                            <?= $player->iconLoan() ?>
                        </div>
                    </div>
                <?php endif ?>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= Yii::t('frontend', 'views.player.player.position') ?>:
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= $player->position() ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= Yii::t('frontend', 'views.player.player.special') ?>:
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= $player->special() ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= Yii::t('frontend', 'views.player.player.salary') ?>:
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= FormatHelper::asCurrency($player->salary) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= Yii::t('frontend', 'views.player.player.price') ?>:
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= FormatHelper::asCurrency($player->price) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
