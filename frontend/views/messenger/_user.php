<?php

// TODO refactor

use common\models\db\Message;
use yii\helpers\Html;

/**
 * @var Message $model
 */

$user = $model->from_user_id === Yii::$app->user->id ? $model->toUser : $model->fromUser;

?>
<div class="row border-top">
    <div class="col-lg-1 col-md-1 col-sm-1 hidden-xs text-center">
        <?= $user->smallLogo() ?>
    </div>
    <div class="col-lg-11 col-md-11 col-sm-11 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong">
                <?= $user->getUserLink() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= Yii::t('frontend', 'views.messenger.user.visit') ?> <?= $user->lastVisit() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= Yii::t('frontend', 'views.messenger.user.rating') ?> <span
                        class="strong"><?= $user->rating ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= Yii::t('frontend', 'views.messenger.user.team') ?>
                <?php foreach ($user->teams as $team) : ?>
                    <br/>
                    <?= $team->getTeamImageLink() ?>
                <?php endforeach ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php if (!$model->read) : ?>
                    <?= Html::a(Yii::t('frontend', 'views.messenger.user.link.read'), ['view', 'id' => $user->id], ['class' => 'strong']) ?> |
                <?php endif ?>
                <?= Html::a(Yii::t('frontend', 'views.messenger.user.link.write'), ['view', 'id' => $user->id]) ?>
            </div>
        </div>
    </div>
</div>
