<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Federation;
use common\models\db\News;
use common\models\db\NewsComment;
use common\models\db\User;
use common\models\db\UserBlock;
use common\models\db\UserBlockType;
use rmrevin\yii\fontawesome\FAB;
use rmrevin\yii\fontawesome\FAS;
use rmrevin\yii\fontawesome\FontAwesome;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

/**
 * @var UserBlock $allCommentBlock
 * @var ActiveDataProvider $dataProvider
 * @var Federation $federation
 * @var NewsComment $model
 * @var News $news
 * @var UserBlock $newsCommentBlock
 * @var User $user
 */

$user = Yii::$app->user->identity;

print $this->render('_federation', [
    'federation' => $federation,
]);

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><?= Yii::t('frontend', 'views.federation.news-view.h3') ?></h3>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-1 strong">
        <?= $news->title ?>
        <?php if (!Yii::$app->user->isGuest && $news->user_id === Yii::$app->user->id) : ?>
            <span class="text-size-3 font-grey">
                <?= Html::a(
                    FAS::icon(FontAwesome::_PENCIL_ALT),
                    ['news-update', 'id' => $news->federation_id, 'newsId' => $news->id]
                ) ?>
                |
                <?= Html::a(
                    FAB::icon(FontAwesome::_TRASH_ALT),
                    ['news-delete', 'id' => $news->federation_id, 'newsId' => $news->id]
                ) ?>
            </span>
        <?php endif ?>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-size-3 font-grey">
        <?= FormatHelper::asDateTime($news->date) ?>
        -
        <?= $news->user->getUserLink(['class' => 'strong']) ?>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= nl2br($news->text) ?>
    </div>
</div>
<?php if ($dataProvider->models) : ?>
    <div class="row margin-top">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            <span class="strong"><?= Yii::t('frontend', 'views.federation.news-view.comments') ?></span>
        </div>
    </div>
    <div class="row">
        <?php

        try {
            print ListView::widget([
                'dataProvider' => $dataProvider,
                'itemOptions' => ['class' => 'row border-top'],
                'itemView' => '_comment',
            ]);
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
                <?= Yii::t('frontend', 'views.federation.news-view.blocked-confirm') ?>
            </div>
        </div>
    <?php elseif ($newsCommentBlock = $user->getUserBlock(UserBlockType::TYPE_COMMENT_NEWS)->one()) : ?>
        <div class="row margin-top">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert warning">
                <?= Yii::t('frontend', 'views.federation.news-view.blocked-reason', [
                    'date' => FormatHelper::asDateTime($newsCommentBlock->date),
                    'text' => $newsCommentBlock->userBlockReason->text,
                ]) ?>
            </div>
        </div>
    <?php elseif ($allCommentBlock = $user->getUserBlock(UserBlockType::TYPE_COMMENT)->one()) : ?>
        <div class="row margin-top">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert warning">
                <?= Yii::t('frontend', 'views.federation.news-view.blocked-reason', [
                    'date' => FormatHelper::asDateTime($allCommentBlock->date),
                    'text' => $allCommentBlock->userBlockReason->text,
                ]) ?>
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
        ]) ?>
        <?= $form->field($model, 'text')->textarea(['rows' => 5])->label(Yii::t('frontend', 'views.federation.news-view.label.text')) ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <?= Html::submitButton(Yii::t('frontend', 'views.federation.news-view.submit'), ['class' => 'btn margin']) ?>
            </div>
        </div>
        <?php ActiveForm::end() ?>
    <?php endif ?>
<?php endif ?>
