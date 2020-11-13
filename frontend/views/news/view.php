<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\News;
use common\models\db\NewsComment;
use common\models\db\User;
use common\models\db\UserBlock;
use common\models\db\UserBlockType;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

/**
 * @var UserBlock $allCommentBlock
 * @var ActiveDataProvider $dataProvider
 * @var NewsComment $model
 * @var News $news
 * @var UserBlock $newsCommentBlock
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
<?php if ($dataProvider->models) : ?>
    <div class="row margin-top">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            <span class="strong">Последние комментарии:</span>
        </div>
    </div>
    <div class="row">
        <?php

// TODO refactor

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
<?php endif ?>
<?php if (!Yii::$app->user->isGuest) : ?>
    <?php if (!$user->date_confirm) : ?>
        <div class="row margin-top">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert warning">
                Вам заблокирован доступ к комментированию новостей
                <br/>
                Причина - ваш почтовый адрес не подтверждён
            </div>
        </div>
    <?php elseif ($newsCommentBlock = $user->getUserBlock(UserBlockType::TYPE_COMMENT_NEWS)->one()) : ?>
        <div class="row margin-top">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert warning">
                Вам заблокирован доступ к комментированию новостей до
                <?= FormatHelper::asDateTime($newsCommentBlock->date) ?>
                <br/>
                Причина - <?= $newsCommentBlock->userBlockReason->text ?>
            </div>
        </div>
    <?php elseif ($allCommentBlock = $user->getUserBlock(UserBlockType::TYPE_COMMENT)->one()) : ?>
        <div class="row margin-top">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert warning">
                Вам заблокирован доступ к комментированию новостей до
                <?= FormatHelper::asDateTime($allCommentBlock->date) ?>
                <br/>
                Причина - <?= $allCommentBlock->userBlockReason->text ?>
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
        <?= $form->field($model, 'text')->textarea()->label('Ваш комментарий:') ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <?= Html::submitButton(
                    'Комментировать',
                    ['class' => 'btn margin']
                ) ?>
            </div>
        </div>
        <?php

// TODO refactor ActiveForm::end() ?>
    <?php endif ?>
<?php endif ?>
