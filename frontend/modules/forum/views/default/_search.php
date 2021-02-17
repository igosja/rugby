<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use common\models\db\ForumMessage;
use yii\helpers\Html;

/**
 * @var ForumMessage $model
 */

?>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?= $model->user->getUserLink(['color' => true]) ?>
        </div>
    </div>
    <div class="row text-size-2 hidden-xs">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?= Yii::t('frontend', 'modules.forum.views.date-register') ?>:
            <?= FormatHelper::asDate($model->user->date_register) ?>
        </div>
    </div>
    <div class="row text-size-2 hidden-xs">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?= Yii::t('frontend', 'modules.forum.views.rating') ?>:
            <?= $model->user->rating ?>
        </div>
    </div>
    <div class="row text-size-2 hidden-xs">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?= Yii::t('frontend', 'modules.forum.views.teams') ?>:
            <?php foreach ($model->user->teams as $team) : ?>
                <?= $team->getTeamLink() ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
    <div class="row text-size-2 font-grey">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?= FormatHelper::asDatetime($model->date) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <?= str_ireplace(
                Yii::$app->request->get('q'),
                '<span class="info">' . Yii::$app->request->get('q') . '</span>',
                $model->text
            ) ?>
        </div>
    </div>
    <div class="row text-size-2 font-grey">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <?= Html::a(Yii::t('frontend', 'modules.forum.views.default.to-theme'), ['theme/view', 'id' => $model->forum_theme_id]) ?>
        </div>
    </div>
</div>
