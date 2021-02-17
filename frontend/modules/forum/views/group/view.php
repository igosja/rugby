<?php

// TODO refactor

use common\components\helpers\ErrorHelper;
use common\models\db\ForumGroup;
use common\models\db\User;
use common\models\db\UserBlock;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ListView;

/**
 * @var ActiveDataProvider $dataProvider
 * @var ForumGroup $forumGroup
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
                    Yii::t('frontend', 'modules.forum.views.bread.forum'),
                    ['default/index']
                ) ?>
                /
                <?= Html::a(
                    $forumGroup->forumChapter->name,
                    ['chapter/view', 'id' => $forumGroup->forum_chapter_id]
                ) ?>
                /
                <?= $forumGroup->name ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h1><?= $forumGroup->name ?></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <?php if ($user && $user->date_confirm && (!$userBlockForum || $userBlockForum->date < time()) && (!$userBlockComment || $userBlockComment->date < time())) : ?>
                    <?= Html::a(
                        Yii::t('frontend', 'modules.forum.views.group.view.link.create'),
                        ['theme/create', 'groupId' => Yii::$app->request->get('id')],
                        ['class' => 'btn margin']
                    ) ?>
                <?php endif; ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                <?= $this->render('/default/_searchForm') ?>
            </div>
        </div>
        <div class="row forum-row-head">
            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                <?= Yii::t('frontend', 'modules.forum.views.group.view.theme') ?>
            </div>
            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center">
                <span class="hidden-lg hidden-md hidden-sm"
                      title="<?= Yii::t('frontend', 'modules.forum.views.group.view.title.answer') ?>"><?= Yii::t('frontend', 'modules.forum.views.group.view.th.answer') ?></span>
                <span class="hidden-xs"><?= Yii::t('frontend', 'modules.forum.views.group.view.title.answer') ?></span>
            </div>
            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center">
                <span class="hidden-lg hidden-md"
                      title="<?= Yii::t('frontend', 'modules.forum.views.group.view.title.view') ?>"><?= Yii::t('frontend', 'modules.forum.views.group.view.th.view') ?></span>
                <span class="hidden-sm hidden-xs"><?= Yii::t('frontend', 'modules.forum.views.group.view.title.view') ?></span>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                <?= Yii::t('frontend', 'modules.forum.views.group.view.last') ?>
            </div>
        </div>
        <?php

        try {
            print ListView::widget([
                'dataProvider' => $dataProvider,
                'itemOptions' => ['class' => 'row forum-row'],
                'itemView' => '_theme',
                'summary' => false,
                'viewParams' => [
                    'user' => $user,
                ],
            ]);
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        ?>
    </div>
</div>
