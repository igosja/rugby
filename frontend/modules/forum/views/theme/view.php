<?php

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\ForumMessage;
use common\models\db\ForumTheme;
use common\models\db\User;
use common\models\db\UserBlock;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

/**
 * @var ActiveDataProvider $dataProvider
 * @var ForumTheme $forumTheme
 * @var ForumMessage $model
 * @var View $this
 * @var User $user
 * @var UserBlock $userBlockComment
 * @var UserBlock $userBlockForum
 */

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-2">
                <?= Html::a(
                    'Форум',
                    ['default/index']
                ) ?>
                /
                <?= Html::a(
                    $forumTheme->forumGroup->forumChapter->name,
                    ['chapter/view', 'id' => $forumTheme->forumGroup->forum_chapter_id]
                ) ?>
                /
                <?= Html::a(
                    $forumTheme->forumGroup->name,
                    ['group/view', 'id' => $forumTheme->forum_group_id]
                ) ?>
                /
                <?= $forumTheme->name ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h1><?= $forumTheme->name ?></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                <?= $this->render('/default/_searchForm') ?>
            </div>
        </div>
        <?php

        try {
            print ListView::widget([
                'dataProvider' => $dataProvider,
                'itemOptions' => ['class' => 'row forum-row forum-striped'],
                'itemView' => '_message',
                'summary' => false,
            ]);
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        ?>
    </div>
</div>
<?php if (!Yii::$app->user->isGuest) : ?>
    <?php if (!$user->date_confirm) : ?>
        <div class="row margin-top">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert warning">
                Вам заблокирован доступ к форуму
                <br/>
                Причина - ваш почтовый адрес не подтверждён
            </div>
        </div>
    <?php elseif ($userBlockForum && $userBlockForum->date >= time()) : ?>
        <div class="row margin-top">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert warning">
                Вам заблокирован доступ к форуму до
                <?= FormatHelper::asDatetime($userBlockForum->date) ?>
                <br/>
                Причина - <?= $userBlockForum->userBlockReason->text ?>
            </div>
        </div>
    <?php elseif ($userBlockComment && $userBlockComment->date >= time()) : ?>
        <div class="row margin-top">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert warning">
                Вам заблокирован доступ к форуму до
                <?= FormatHelper::asDateTime($userBlockComment->date) ?>
                <br/>
                Причина - <?= $userBlockComment->userBlockReason->text ?>
            </div>
        </div>
    <?php else: ?>
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
        ]); ?>
        <?= $form->field($model, 'text')->textarea(['raw' => 10])->label('Ваш ответ:') ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <?= Html::submitButton('Ответить', ['class' => 'btn margin']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    <?php endif; ?>
<?php endif; ?>
