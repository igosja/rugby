<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use common\models\db\User;
use rmrevin\yii\fontawesome\FAS;
use yii\helpers\Html;

/**
 * @var User $user
 */
$user = User::find()
    ->where(['id' => Yii::$app->request->get('id', Yii::$app->user->id)])
    ->limit(1)
    ->one();

?>
<div class="row margin-top">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-3 col-xs-4 text-center team-logo-div">
                <?= $user->logo() ?>
            </div>
            <div class="col-lg-9 col-md-8 col-sm-9 col-xs-8">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-1 strong">
                        <?= $user->fullName() ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-3">
                        Последний визит: <?= $user->lastVisit() ?>
                    </div>
                </div>
                <div class="row margin-top">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        Ник:
                        <?= $user->iconVip() ?>
                        <span class="strong"><?= Html::encode($user->login) ?></span>
                        <?php if ($user->canDialog()) : ?>
                            <?= Html::a(
                                FAS::icon(FAS::_ENVELOPE),
                                ['messenger/view', 'id' => $user->id]
                            ) ?>
                            <?= Html::a(
                                $user->blacklistIcon(),
                                ['user/blacklist', 'id' => $user->id]
                            ) ?>
                        <?php endif ?>
                        <?php if ($user->activeUserHoliday) : ?>
                            <span class="italic">(в отпуске)</span>
                        <?php endif ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        Личный счет:
                        <span class="strong"><?= FormatHelper::asCurrency($user->finance) ?></span>
                    </div>
                </div>
                <?php if (!Yii::$app->user->isGuest && Yii::$app->request->get('id') === Yii::$app->user->id) : ?>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            Денежный счет: <span class="strong"><?= $user->money ?> ед.</span>
                        </div>
                    </div>
                <?php endif ?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        Рейтинг: <span class="strong"><?= $user->rating ?></span>
                    </div>
                </div>
                <?php if (!Yii::$app->user->isGuest && Yii::$app->request->get('id') === Yii::$app->user->id) : ?>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            VIP-клуб:
                            <span class="strong">
                                <?php if ($user->isVip()) {
                                    $vipText = 'до ' . FormatHelper::asDatetime($user->date_vip);
                                } else {
                                    $vipText = 'не активирован';
                                } ?>
                                <?= Html::a($vipText, ['vip/index']) ?>
                            </span>
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-1 strong">
                Профиль менеджера
            </div>
        </div>
        <div class="row margin-top">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                День рождения:
                <span class="strong">
                    <?= $user->birthDay() ?>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                Пол: <span class="strong"><?= $user->sex->name ?? '' ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                Откуда: <span class="strong"><?= $user->userFrom() ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                Дата регистрации:
                <span class="strong"><?= FormatHelper::asDate($user->date_register) ?></span>
            </div>
        </div>
        <?php if (!Yii::$app->user->isGuest && (int)Yii::$app->request->get('id') === Yii::$app->user->id) : ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    Я в социальных сетях:
                    <span class="strong">
                        <?= $user->socialLinks() ?>
                    </span>
                    <span class="strong">
                        <?= Html::a(
                            FAS::icon(FAS::_ARROW_CIRCLE_RIGHT),
                            ['user/social']
                        ) ?>
                    </span>
                </div>
            </div>
        <?php endif ?>
    </div>
</div>
