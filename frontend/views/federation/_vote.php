<?php

// TODO refactor

use common\components\helpers\FormatHelper;
use common\models\db\Vote;
use common\models\db\VoteStatus;
use yii\helpers\Html;

/**
 * @var Vote $model
 */

?>
<div class="row border-top">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                <?= Html::a(
                    '<span class="strong">' . $model->text . '</span>',
                    ['vote/view', 'id' => $model->id]
                ) ?>
                <?php if ($model->vote_status_id === VoteStatus::NEW && !Yii::$app->user->isGuest && $model->user_id === Yii::$app->user->id) : ?>
                    <span class="text-size-3 font-grey">
                        <?= Html::a(
                            Yii::t('frontend', 'views.federation.vote.link.delete'),
                            ['vote-delete', 'id' => $model->federation_id, 'voteId' => $model->id]
                        ) ?>
                    </span>
                <?php endif ?>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                <?= $model->voteStatus->name ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-3">
                <?= Yii::t('frontend', 'views.federation.vote.author') ?>
                <?= $model->user->getUserLink(['color' => true]) ?>,
                <?= FormatHelper::asDateTime($model->date) ?>
            </div>
        </div>
        <?php foreach ($model->voteAnswers as $answer) : ?>
            <div class="row">
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <?= $answer->text ?>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
                    <?= count($answer->voteUsers) ?>
                    (%)
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>