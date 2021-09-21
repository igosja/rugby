<?php

// TODO refactor

use common\models\db\Support;
use yii\helpers\Html;

/**
 * @var Support $model
 */

?>
<div class="row border-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 strong">
        <?= $model->user->getUserLink() ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= Yii::t('frontend', 'views.federation.support-user.last-visit') ?> <?= $model->user->lastVisit() ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= Yii::t('frontend', 'views.federation.support-user.rating') ?> <span
                class="strong"><?= $model->user->rating ?></span>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= Yii::t('frontend', 'views.federation.support-user.team') ?>
        <?php foreach ($model->user->teams as $team) : ?>
            <br/>
            <?= $team->getTeamImageLink() ?>
        <?php endforeach ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php if (!$model->read) { ?>
            <?= Html::a(
                Yii::t('frontend', 'views.federation.support-user.link.new'),
                ['/federation/support/president-view', 'id' => $model->federation_id, 'user_id' => $model->user->id],
                ['class' => 'strong']
            ) ?>
            |
        <?php } ?>
        <?= Html::a(
            Yii::t('frontend', 'views.federation.support-user.link.write'),
            ['/federation/support/president-view', 'id' => $model->federation_id, 'user_id' => $model->user->id]
        ) ?>
    </div>
</div>