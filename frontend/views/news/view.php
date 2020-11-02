<?php

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\News;
use common\models\db\NewsComment;
use common\models\db\User;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

/**
 * @var ActiveDataProvider $dataProvider
 * @var NewsComment $model
 * @var News $news
 * @var User $user
 */

$user = $this->context->user;

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h1>Комментарии к новостям Лиги</h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-1 strong">
        <?= $news->title ?>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-3 font-grey">
        <?= FormatHelper::asDateTime($news->date) ?>
        -
        <?= $news->user->getUserLink(['class' => 'strong']) ?>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $news->text ?>
    </div>
</div>
<?php
if ($dataProvider->models) : ?>
    <div class="row margin-top">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            <span class="strong">Последние комментарии:</span>
        </div>
    </div>
    <div class="row">
        <?php

        try {
            print ListView::widget([
                                       'dataProvider' => $dataProvider,
                                       'itemOptions' => ['class' => 'row border-top'],
                                       'itemView' => '_comment',
                                   ]
            );
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        ?>
    </div>
<?php
endif ?>
<?php
if (!Yii::$app->user->isGuest) : ?>
    <?php
    if (!$user->user_date_confirm) : ?>
        <div class="row margin-top">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert warning">
                Вам заблокирован доступ к комментированию новостей
                <br/>
                Причина - ваш почтовый адрес не подтверждён
            </div>
        </div>
    <?php
    elseif ($user->getCommentNewsBlock()) : ?>
        <div class="row margin-top">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert warning">
                Вам заблокирован доступ к комментированию новостей до
                <?= FormatHelper::asDateTime($user->getCommentNewsBlock()->user_block_date) ?>
                <br/>
                Причина - <?= $user->getCommentNewsBlock()->userBlockReason->user_block_reason_text ?>
            </div>
        </div>
    <?php else : ?>
        <?php $form = ActiveForm::begin([
            'fieldConfig' => [
                'errorOptions' => [
                    'class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center notification-error',
                    'tag' => 'div'
                ],
                'options' => ['class' => 'row'],
                'template' =>
                    '<div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center strong">{label}</div>
                    </div>
                    <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">{input}</div>
                    </div>
                    <div class="row">{error}</div>',
            ],
                                        ]
        ) ?>
        <?= $form
            ->field($model, 'text')
            ->textarea()
            ->label('Ваш комментарий:') ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <?= Html::submitButton(
                    'Комментировать',
                    ['class' => 'btn margin']
                ) ?>
            </div>
        </div>
        <?php
        ActiveForm::end() ?>
    <?php
    endif ?>
<?php
endif ?>
